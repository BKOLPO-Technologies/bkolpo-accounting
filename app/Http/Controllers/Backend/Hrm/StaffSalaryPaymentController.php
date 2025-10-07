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
            foreach ($request->staff_id as $index => $staffId) {
                $month = str_pad($request->month, 2, '0', STR_PAD_LEFT);
                $salaryMonth = "{$request->year}-{$month}-01";
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

                    $status = 'partial_paid';
                    if ($paymentAmount == 0) {
                        $status = 'unpaid';
                    } elseif ($due == 0) {
                        $status = 'paid';
                    }

                    // Company info for voucher number
                    $companyInfo = get_company();
                    $currentMonth = now()->format('m');
                    $fiscalYearWithoutHyphen = str_replace('-', '', $companyInfo->fiscal_year);
                    $voucherPrefix = 'BCL-SAL-' . $fiscalYearWithoutHyphen . $currentMonth;

                    // Generate unique voucher number
                    $lastVoucher = JournalVoucher::latest()->first();
                    $nextNo = $lastVoucher ? $lastVoucher->id + 1 : 1;
                    $voucherNo = $voucherPrefix . str_pad($nextNo, 4, '0', STR_PAD_LEFT);

                    // 🔹 Update salary payment with voucher_no
                    $salary->update([
                        'ledger_id' => $ledger->id,
                        'payment_method' => $payment_method,
                        'payment_amount' => $paymentAmount,
                        'payment_date' => now(),
                        'status' => $status,
                        'note' => $note,
                        'voucher_no' => $voucherNo, 
                    ]);

                    // Get Ledger Info
                    $cashBankLedger = $ledger; // নগদ/ব্যাংক
                    $salaryLedger = Ledger::where('type', 'Salary')->first();

                    if ($cashBankLedger && $salaryLedger) {
                        // Create Journal Voucher
                        $journalVoucher = JournalVoucher::create([
                            'transaction_code' => $voucherNo,
                            'transaction_date' => now(),
                            'company_id' => $companyInfo->id,
                            'branch_id' => $companyInfo->branch->id,
                            'description' => 'Salary payment for ' . \Carbon\Carbon::parse($salaryMonth)->format('F Y'),
                            'status' => 1,
                        ]);

                        // Journal Entry 1: Salary Expense (Debit)
                        JournalVoucherDetail::create([
                            'journal_voucher_id' => $journalVoucher->id,
                            'ledger_id' => $salaryLedger->id,
                            'reference_no' => $voucherNo,
                            'description' => 'Salary Expense',
                            'debit' => $paymentAmount,
                            'credit' => 0,
                        ]);

                        // Journal Entry 2: Cash/Bank (Credit)
                        JournalVoucherDetail::create([
                            'journal_voucher_id' => $journalVoucher->id,
                            'ledger_id' => $cashBankLedger->id,
                            'reference_no' => $voucherNo,
                            'description' => 'Payment from ' . $cashBankLedger->name,
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
        ]);

        DB::beginTransaction();
        try {
            $salary = StaffSalary::findOrFail($request->salary_id);
            $ledger = Ledger::findOrFail($request->payment_method); // Cash/Bank ledger
            $salaryLedger = Ledger::where('type', 'Salary')->firstOrFail();

            // Update salary payment (cumulative)
            $salary->payment_amount += $request->payment_amount;
            $salary->ledger_id = $ledger->id;
            $salary->payment_method = $ledger->type;
            $salary->payment_date = now();

            // status calculation
            $due = $salary->net - $salary->payment_amount;
            if ($salary->payment_amount == 0) {
                $salary->status = 'unpaid';
            } elseif ($due > 0) {
                $salary->status = 'partial_paid';
            } else {
                $salary->status = 'paid';
            }

            // Prepare / ensure voucher number
            $voucherNo = $salary->voucher_no;

            if (! $voucherNo) {
                // create a new voucher
                $companyInfo = get_company();
                $currentMonth = now()->format('m');
                $fiscalYear = str_replace('-', '', $companyInfo->fiscal_year);
                $prefix = 'BCL-SAL-' . $fiscalYear . $currentMonth;

                $lastVoucher = JournalVoucher::latest()->first();
                $nextNo = $lastVoucher ? $lastVoucher->id + 1 : 1;
                $voucherNo = $prefix . str_pad($nextNo, 4, '0', STR_PAD_LEFT);

                $journalVoucher = JournalVoucher::create([
                    'transaction_code' => $voucherNo,
                    'transaction_date' => now(),
                    'company_id' => $companyInfo->id,
                    'branch_id' => $companyInfo->branch->id,
                    'description' => 'Salary payment for ' . \Carbon\Carbon::parse($salary->salary_month)->format('F Y'),
                    'status' => 1,
                    'salary_id' => $salary->id,
                ]);

                // create two details (debit salary, credit cash/bank)
                JournalVoucherDetail::create([
                    'journal_voucher_id' => $journalVoucher->id,
                    'ledger_id' => $salaryLedger->id,
                    'reference_no' => $voucherNo,
                    'description' => 'Salary Expense',
                    'debit' => $salary->payment_amount,
                    'credit' => 0,
                ]);

                JournalVoucherDetail::create([
                    'journal_voucher_id' => $journalVoucher->id,
                    'ledger_id' => $ledger->id,
                    'reference_no' => $voucherNo,
                    'description' => 'Payment from ' . $ledger->name,
                    'debit' => 0,
                    'credit' => $salary->payment_amount,
                ]);

                // save voucher_no to salary
                $salary->voucher_no = $voucherNo;
            } else {
                // existing voucher: find it
                $journalVoucher = JournalVoucher::where('transaction_code', $voucherNo)->first();

                if (! $journalVoucher) {
                    // (fallback) recreate voucher if not found
                    $companyInfo = get_company();
                    $currentMonth = now()->format('m');
                    $fiscalYear = str_replace('-', '', $companyInfo->fiscal_year);
                    $prefix = 'BCL-SAL-' . $fiscalYear . $currentMonth;

                    $lastVoucher = JournalVoucher::latest()->first();
                    $nextNo = $lastVoucher ? $lastVoucher->id + 1 : 1;
                    $voucherNo = $prefix . str_pad($nextNo, 4, '0', STR_PAD_LEFT);

                    $journalVoucher = JournalVoucher::create([
                        'transaction_code' => $voucherNo,
                        'transaction_date' => now(),
                        'company_id' => $companyInfo->id,
                        'branch_id' => $companyInfo->branch->id,
                        'description' => 'Salary payment for ' . \Carbon\Carbon::parse($salary->salary_month)->format('F Y'),
                        'status' => 1,
                        'salary_id' => $salary->id,
                    ]);

                    // create details fresh
                    JournalVoucherDetail::create([
                        'journal_voucher_id' => $journalVoucher->id,
                        'ledger_id' => $salaryLedger->id,
                        'reference_no' => $voucherNo,
                        'description' => 'Salary Expense',
                        'debit' => $salary->payment_amount,
                        'credit' => 0,
                    ]);

                    JournalVoucherDetail::create([
                        'journal_voucher_id' => $journalVoucher->id,
                        'ledger_id' => $ledger->id,
                        'reference_no' => $voucherNo,
                        'description' => 'Payment from ' . $ledger->name,
                        'debit' => 0,
                        'credit' => $salary->payment_amount,
                    ]);

                    $salary->voucher_no = $voucherNo;
                } else {
                    // get details as a Collection (never null)
                    $details = $journalVoucher->details()->get();

                    if ($details->isEmpty()) {
                        // if there are no details for some reason, create them
                        JournalVoucherDetail::create([
                            'journal_voucher_id' => $journalVoucher->id,
                            'ledger_id' => $salaryLedger->id,
                            'reference_no' => $journalVoucher->transaction_code,
                            'description' => 'Salary Expense',
                            'debit' => $salary->payment_amount,
                            'credit' => 0,
                        ]);

                        JournalVoucherDetail::create([
                            'journal_voucher_id' => $journalVoucher->id,
                            'ledger_id' => $ledger->id,
                            'reference_no' => $journalVoucher->transaction_code,
                            'description' => 'Payment from ' . $ledger->name,
                            'debit' => 0,
                            'credit' => $salary->payment_amount,
                        ]);
                    } else {
                        // update existing details:
                        foreach ($details as $detail) {
                            // If this detail is the Salary ledger (debit), update debit
                            if ($detail->ledger_id == $salaryLedger->id || $detail->debit > 0) {
                                $detail->update([
                                    'ledger_id' => $salaryLedger->id,
                                    'description' => 'Salary Expense',
                                    'debit' => $salary->payment_amount,
                                    'credit' => 0,
                                ]);
                            } else {
                                // otherwise treat as cash/bank credit detail — update ledger_id & credit
                                $detail->update([
                                    'ledger_id' => $ledger->id,
                                    'description' => 'Payment from ' . $ledger->name,
                                    'debit' => 0,
                                    'credit' => $salary->payment_amount,
                                ]);
                            }
                        }
                    }
                }
            }

            $salary->save();
            DB::commit();

            return redirect()->back()->with('success', 'Salary payment and journal updated successfully!');
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

            $journalVoucher = JournalVoucher::where('transaction_code', $salary->voucher_no)->first();
            // 🔹 Journal Voucher delete
            if ($journalVoucher) {
                JournalVoucherDetail::where('journal_voucher_id', $journalVoucher->id)->delete();
                $journalVoucher->delete();
            }

            // 🔹 salay delete
            $salary->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Salary record and related journal deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error deleting payment receipt', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->back()->with('error', 'Failed to delete payment receipt! '.$e->getMessage());
        }
    }

}
