<?php

namespace App\Http\Controllers\Backend\Hrm;

use App\Http\Controllers\Controller;
use App\Models\Ledger;
use App\Models\Staff;
use App\Models\StaffSalary;
use App\Models\JournalVoucher;
use App\Models\JournalVoucherDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StaffSalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = StaffSalary::with('staff');

        if ($request->month && $request->year) {
            // salary_month date format: YYYY-MM-01
            $salaryMonth = $request->year . '-' . str_pad($request->month, 2, '0', STR_PAD_LEFT) . '-01';
            $query->where('salary_month', $salaryMonth);
        }

        $pageTitle = 'Staff Salary Generate List';

        // pagination
        $salaries = $query->latest()->paginate(10);

        return view('backend.admin.hrm.salary.index', compact('salaries','pageTitle'));
    }

    

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $pageTitle = 'Staff Salary Generate';

        $month = $request->month ?? date('m');
        $year = $request->year ?? date('Y');
        $salaryMonth = $year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01';
        
        $staffs = Staff::with('salaryStructure')
            ->where('status', 1)
            ->whereDoesntHave('salaries', function ($query) use ($salaryMonth) {
                $query->where('salary_month', $salaryMonth)
                    ->where('status', 'Paid');
            })
            ->latest()
            ->get();

        $ledgers = Ledger::whereIn('type', ['Bank', 'Cash'])->get();

        return view('backend.admin.hrm.salary.create', compact('pageTitle', 'staffs', 'ledgers', 'month', 'year'));
    }



    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'month' => 'required|numeric|min:1|max:12',
                'year' => 'required|numeric',
                'staff_id' => 'required|array',
            ]);

            $salaryMonth = Carbon::createFromDate($request->year, $request->month, 1)->toDateString();

            foreach ($request->staff_id as $index => $staffId) {
                $staff = Staff::find($staffId);
                $basic = $request->basic_salary[$index] ?? 0;
                $hra = $request->hra[$index] ?? 0;
                $medical = $request->medical[$index] ?? 0;
                $conveyance = $request->conveyance[$index] ?? 0;
                $pf = $request->pf[$index] ?? 0;
                $tax = $request->tax[$index] ?? 0;
                $other = $request->other_deductions[$index] ?? 0;

                $gross = $basic + $hra + $medical + $conveyance;
                $net = $gross - ($pf + $tax + $other);
                $paymentAmount = $request->payment_amount[$index] ?? $net;

                // check existing
                $exists = StaffSalary::where('staff_id', $staffId)
                    ->where('salary_month', $salaryMonth)
                    ->exists();

                if ($exists) continue;

                // Save Salary Record
                StaffSalary::create([
                    'staff_id' => $staffId,
                    'salary_month' => $salaryMonth,
                    'basic' => $basic,
                    'hra' => $hra,
                    'medical' => $medical,
                    'conveyance' => $conveyance,
                    'pf' => $pf,
                    'tax' => $tax,
                    'other_deduction' => $other,
                    'gross' => $gross,
                    'net' => $net,
                    'status' => 'Pending',
                ]);

                // Prepare voucher info
                $companyInfo = get_company();
                $currentMonth = now()->format('m');
                $fiscalYearWithoutHyphen = str_replace('-', '', $companyInfo->fiscal_year);
                $voucherPrefix = 'BCL-SAL-SHEET-' . $fiscalYearWithoutHyphen . $currentMonth;

                $lastVoucher = JournalVoucher::latest()->first();
                $nextNo = $lastVoucher ? $lastVoucher->id + 1 : 1;
                $voucherNo = $voucherPrefix . str_pad($nextNo, 4, '0', STR_PAD_LEFT);

                // ✅ Ledger for Salary Expense & Salary Payable
                $salaryExpense = Ledger::where('type', 'Salary')->first();
                $salaryPayable = Ledger::where('type', 'Salary Payable')->first();

                if ($salaryExpense && $salaryPayable) {
                    // Create Voucher Master
                    $journalVoucher = JournalVoucher::create([
                        'transaction_code' => $voucherNo,
                        'transaction_date' => now(),
                        'company_id' => $companyInfo->id,
                        'branch_id' => $companyInfo->branch->id ?? null,
                        'description' => 'Salary sheet for ' . Carbon::parse($salaryMonth)->format('F Y'),
                        'status' => 1,
                    ]);

                    $staffName = $staff ? $staff->name : 'Unknown Staff';

                    // Journal Entry 1: Salary Expense (Debit)
                    JournalVoucherDetail::create([
                        'journal_voucher_id' => $journalVoucher->id,
                        'ledger_id' => $salaryExpense->id,
                        'reference_no' => $voucherNo,
                        'description' => 'Salary Expense for ' . $staffName,
                        'debit' => $paymentAmount,
                        'credit' => 0,
                    ]);

                    // Journal Entry 2: Salary Payable (Credit)
                    JournalVoucherDetail::create([
                        'journal_voucher_id' => $journalVoucher->id,
                        'ledger_id' => $salaryPayable->id,
                        'reference_no' => $voucherNo,
                        'description' => 'Salary Payable for ' . $staffName,
                        'debit' => 0,
                        'credit' => $paymentAmount,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.staff.salary.index')
                ->with('success', 'Staff salary generated and journal entries created successfully!');

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Salary generation error: ' . $e->getMessage());
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }



    public function paySalary(Request $request)
    {
        $request->validate([
            'salary_id' => 'required|exists:staff_salaries,id',
            'payment_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
        ]);

        $salary = StaffSalary::findOrFail($request->salary_id);

        // Update cumulative payment
        $salary->payment_amount += $request->payment_amount;

        // Update status
        if ($salary->payment_amount >= $salary->net_salary) {
            $salary->status = 'Paid';
        } elseif ($salary->payment_amount > 0) {
            $salary->status = 'partial_paid';
        } else {
            $salary->status = 'Pending';
        }

        $ledger = Ledger::findOrFail($request->payment_method);
        $payment_method = $ledger->type; // Cash or Bank

        // Update payment method
        $salary->payment_method = $request->payment_method;
        $salary->ledger_id = $request->payment_method;
        $salary->save();

        return redirect()->back()->with('success', 'Payment updated successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $salary = StaffSalary::with('staff')->findOrFail($id);
        $pageTitle = 'Staff Salary Details';
        return view('backend.admin.hrm.salary.show', compact('salary','pageTitle'));
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
        $salary = StaffSalary::findOrFail($id);

        $journalVoucher = JournalVoucher::where('transaction_code', $salary->voucher_no)->first();
        // 🔹 Journal Voucher delete
        if ($journalVoucher) {
            JournalVoucherDetail::where('journal_voucher_id', $journalVoucher->id)->delete();
            $journalVoucher->delete();
        }

        $salary->delete();

        return redirect()->route('admin.staff.salary.index')
                        ->with('success', 'Salary deleted successfully!');
    }

}
