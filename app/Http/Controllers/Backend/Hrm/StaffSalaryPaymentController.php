<?php

namespace App\Http\Controllers\Backend\Hrm;

use App\Http\Controllers\Controller;
use App\Models\Ledger;
use App\Models\Staff;
use App\Models\StaffSalary;
use App\Models\JournalVoucher;
use App\Models\JournalVoucherDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class StaffSalaryPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index(Request $request)
    {
        $query = StaffSalary::with('staff')
            ->where('status', '!=', 'pending');

        if ($request->month && $request->year) {
            $salaryMonth = $request->year.'-'.str_pad($request->month, 2, '0', STR_PAD_LEFT).'-01';
            $query->where('salary_month', $salaryMonth);
        }

        $pageTitle = 'Staff Salary Payment List';
        $salaries = $query->latest()->paginate(10);

        $ledgers = Ledger::whereIn('type', ['Bank', 'Cash'])->get();

        return view('backend.admin.hrm.salary.payment.index', compact('salaries','ledgers', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $pageTitle = 'Staff Salary Payment';

        // Get selected or current month/year
        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');
        $salaryMonth = $year.'-'.str_pad($month, 2, '0', STR_PAD_LEFT).'-01';

        $staffs = Staff::whereHas('salaries', function($query) {
            $query->where('status', 'pending');
        })->with(['salaries' => function($query) {
            $query->where('status', 'pending');
        }])->get();

        if ($staffs->isEmpty()) {
            return redirect()->back()->with('error', 'স্টাফ স্যালারি শীট এখনও তৈরি হয়নি। পেমেন্ট করার আগে দয়া করে শীট তৈরি করুন।');
        }

        // Fetch all available payment ledgers (Cash, Bank)
        $ledgers = Ledger::whereIn('type', ['Bank', 'Cash'])->get();

        return view('backend.admin.hrm.salary.payment.create', compact(
            'pageTitle', 'staffs', 'ledgers', 'month', 'year'
        ));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'month' => 'required|numeric|min:1|max:12',
            'year' => 'required|numeric|min:2000',
            'staff_id' => 'required|array',
            'payment_amount.*' => 'required|numeric|min:0',
            'payment_method.*' => 'required|exists:ledgers,id',
            'note.*' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $month = str_pad($request->month, 2, '0', STR_PAD_LEFT);
            $salaryMonth = "{$request->year}-{$month}-01";

            // Company info and voucher generation (once)
            $companyInfo = get_company();
            $currentMonth = now()->format('m');
            $fiscalYearWithoutHyphen = str_replace('-', '', $companyInfo->fiscal_year);
            $voucherPrefix = 'BCL-SAL-PAY-' . $fiscalYearWithoutHyphen . $currentMonth;

            $lastVoucher = JournalVoucher::latest()->first();
            $nextNo = $lastVoucher ? $lastVoucher->id + 1 : 1;
            $voucherNo = $voucherPrefix . str_pad($nextNo, 4, '0', STR_PAD_LEFT);

            // Create Journal Voucher master (one for all staff)
            $journalVoucher = JournalVoucher::create([
                'transaction_code' => $voucherNo,
                'transaction_date' => now(),
                'company_id' => $companyInfo->id,
                'branch_id' => $companyInfo->branch->id ?? null,
                'description' => 'Salary payment for ' . \Carbon\Carbon::parse($salaryMonth)->format('F Y'),
                'status' => 1,
            ]);

            foreach ($request->staff_id as $index => $staffId) {
                $staff = Staff::find($staffId);
                $staffName = $staff->name;
                $paymentAmount = $request->payment_amount[$index] ?? 0;
                $paymentMethod = $request->payment_method[$index] ?? null;
                $note = $request->note[$index] ?? null;

                $ledger = Ledger::findOrFail($paymentMethod);
                $payment_method = $ledger->type; // Cash or Bank

                $salary = StaffSalary::where('staff_id', $staffId)
                    ->where('salary_month', $salaryMonth)
                    ->first();

                if ($salary) {
                    $due = max($salary->net - $paymentAmount, 0);

                    // Status calculation
                    if ($paymentAmount == 0) {
                        $status = 'unpaid';
                    } elseif ($due > 0) {
                        $status = 'partial_paid';
                    } else {
                        $status = 'paid';
                    }

                    // Update salary payment
                    $salary->update([
                        'ledger_id' => $ledger->id,
                        'payment_method' => $payment_method,
                        'payment_amount' => $paymentAmount,
                        'payment_date' => now(),
                        'status' => $status,
                        'note' => $note,
                    ]);

                    // Get Ledger Info
                    $salaryPayableLedger = Ledger::where('type', 'Salary Payable')->first();

                    if ($salaryPayableLedger) {
                        // Journal entry per staff
                        JournalVoucherDetail::create([
                            'journal_voucher_id' => $journalVoucher->id,
                            'ledger_id' => $salaryPayableLedger->id,
                            'reference_no' => $voucherNo,
                            'description' => 'Salary Payable adjustment for staff  ' . $staffName,
                            'debit' => $paymentAmount,
                            'credit' => 0,
                        ]);

                        JournalVoucherDetail::create([
                            'journal_voucher_id' => $journalVoucher->id,
                            'ledger_id' => $ledger->id,
                            'reference_no' => $voucherNo,
                            'description' => 'Salary payment from ' . $ledger->name . ' for staff ' . $staffName,
                            'debit' => 0,
                            'credit' => $paymentAmount,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.staff.salary.payment.index')
                ->with('success', 'Staff salary payments updated and journal created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $salary = StaffSalary::with('staff')->findOrFail($id);
        $pageTitle = 'Staff Salary Payment Details';
        return view('backend.admin.hrm.salary.payment.show', compact('salary','pageTitle'));
    }

    public function paySalary(Request $request)
    {
        $request->validate([
            'salary_id' => 'required|exists:staff_salaries,id',
            'payment_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|exists:ledgers,id',
            'note' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Fetch salary & ledger
            $salary = StaffSalary::findOrFail($request->salary_id);
            $ledger = Ledger::findOrFail($request->payment_method); // Cash/Bank
            $salaryPayableLedger = Ledger::where('type', 'Salary Payable')->first();

            if (! $salaryPayableLedger) {
                throw new \Exception('Salary Payable ledger not found!');
            }

            $currentPayment = $request->payment_amount;

            // Update salary cumulative
            $salary->payment_amount += $currentPayment;
            $salary->ledger_id = $ledger->id;
            $salary->payment_method = $ledger->type;
            $salary->payment_date = now();
            $salary->note = $request->note ?? $salary->note;

            // Update status
            $due = $salary->net - $salary->payment_amount;
            if ($salary->payment_amount == 0) {
                $salary->status = 'unpaid';
            } elseif ($due > 0) {
                $salary->status = 'partial_paid';
            } else {
                $salary->status = 'paid';
            }

            // Generate or get voucher
            $voucherNo = $salary->voucher_no;
            $companyInfo = get_company();
            $currentMonth = now()->format('m');
            $fiscalYear = str_replace('-', '', $companyInfo->fiscal_year);
            $prefix = 'BCL-SAL-PAY-' . $fiscalYear . $currentMonth;

            if (! $voucherNo) {
                $lastVoucher = JournalVoucher::orderBy('id','desc')->first();
                $nextNo = $lastVoucher ? $lastVoucher->id + 1 : 1;
                $voucherNo = $prefix . str_pad($nextNo, 4, '0', STR_PAD_LEFT);

                $journalVoucher = JournalVoucher::create([
                    'transaction_code' => $voucherNo,
                    'transaction_date' => now(),
                    'company_id' => $companyInfo->id,
                    'branch_id' => $companyInfo->branch->id ?? null,
                    'description' => 'Salary payment for ' . \Carbon\Carbon::parse($salary->salary_month)->format('F Y'),
                    'status' => 1,
                    'salary_id' => $salary->id,
                ]);

                // Save voucher_no to salary
                $salary->voucher_no = $voucherNo;
            } else {
                $journalVoucher = JournalVoucher::where('transaction_code', $voucherNo)->first();

                if (! $journalVoucher) {
                    // Fallback if voucher missing
                    $lastVoucher = JournalVoucher::orderBy('id','desc')->first();
                    $nextNo = $lastVoucher ? $lastVoucher->id + 1 : 1;
                    $voucherNo = $prefix . str_pad($nextNo, 4, '0', STR_PAD_LEFT);

                    $journalVoucher = JournalVoucher::create([
                        'transaction_code' => $voucherNo,
                        'transaction_date' => now(),
                        'company_id' => $companyInfo->id,
                        'branch_id' => $companyInfo->branch->id ?? null,
                        'description' => 'Salary payment for ' . \Carbon\Carbon::parse($salary->salary_month)->format('F Y'),
                        'status' => 1,
                        'salary_id' => $salary->id,
                    ]);

                    $salary->voucher_no = $voucherNo;
                }
            }

            // 🔹 Create new JournalVoucherDetail for current payment
            JournalVoucherDetail::create([
                'journal_voucher_id' => $journalVoucher->id,
                'ledger_id' => $salaryPayableLedger->id,
                'reference_no' => $voucherNo,
                'description' => 'Salary Payable adjustment for ' . \Carbon\Carbon::parse($salary->salary_month)->format('F Y'),
                'debit' => $currentPayment,
                'credit' => 0,
            ]);

            JournalVoucherDetail::create([
                'journal_voucher_id' => $journalVoucher->id,
                'ledger_id' => $ledger->id,
                'reference_no' => $voucherNo,
                'description' => 'Payment from ' . $ledger->name,
                'debit' => 0,
                'credit' => $currentPayment,
            ]);

            $salary->save();
            DB::commit();

            return redirect()->back()->with('success', 'Salary payment updated and journal entry created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }






    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
      public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $salary = StaffSalary::findOrFail($id);

            // 🔹 Delete related Journal Voucher Entries
            $journalDetails = JournalVoucherDetail::where('staff_salary_id', $salary->id)->get();

            foreach ($journalDetails as $detail) {
                // যদি main voucher থাকে, সে voucher ও মুছবো
                $voucher = JournalVoucher::find($detail->journal_voucher_id);
                $detail->delete();
                if ($voucher && $voucher->details()->count() == 0) {
                    $voucher->delete();
                }
            }

            // 🔹 Finally salary delete
            $salary->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Salary and related journal deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }

    public function reverse($id)
    {
        DB::beginTransaction();

        try {
            $salary = StaffSalary::findOrFail($id);

            if ($salary->status !== 'paid' && $salary->status !== 'partial_paid') {
                return back()->with('error', 'Only paid or partially paid salaries can be reversed.');
            }

            // Get Ledger Info
            $salaryPayable = Ledger::where('type', 'Salary Payable')->first();
            $cashBank = Ledger::find($salary->ledger_id);

            // Reverse Voucher Number Generate
            $reverseVoucher = 'REV-' . $salary->voucher_no;

            // Create Reverse Journal Voucher
            $journal = JournalVoucher::create([
                'transaction_code' => $reverseVoucher,
                'transaction_date' => now(),
                'description' => 'Reverse salary payment for ' . $salary->staff->name,
                'status' => 1,
            ]);

            // Reverse Entry - Cash/Bank Debit
            JournalVoucherDetail::create([
                'journal_voucher_id' => $journal->id,
                'ledger_id' => $cashBank->id,
                'debit' => $salary->payment_amount,
                'credit' => 0,
            ]);

            // Reverse Entry - Salary Payable Credit
            JournalVoucherDetail::create([
                'journal_voucher_id' => $journal->id,
                'ledger_id' => $salaryPayable->id,
                'debit' => 0,
                'credit' => $salary->payment_amount,
            ]);

            // Salary status update
            $salary->update([
                'status' => 'reversed',
            ]);

            DB::commit();
            return back()->with('success', 'Salary reversed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Reverse failed: ' . $e->getMessage());
        }
    }


}
