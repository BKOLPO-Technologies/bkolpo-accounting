<?php

namespace App\Http\Controllers\Backend\Hrm;

use App\Http\Controllers\Controller;
use App\Models\Ledger;
use App\Models\Staff;
use App\Models\StaffSalary;
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

                    // Update record
                    $salary->update([
                        'ledger_id' => $request->paymentMethod,
                        'payment_method' => $payment_method,
                        'payment_amount' => $paymentAmount,
                        'payment_date' => now(),
                        'status' => $status,
                        'note' => $note,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.staff.salary.payment.index')->with('success', 'Staff salary payments updated successfully!');
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
        // dd($request->all());
        $request->validate([
            'salary_id' => 'required|exists:staff_salaries,id',
            'payment_amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
        ]);

        $salary = StaffSalary::findOrFail($request->salary_id);

        // ✅ Proceed with payment
        $salary->payment_amount += $request->payment_amount;

        // Calculate due amount
        $due = $salary->net - $salary->payment_amount;

        // Set payment status
        if ($salary->payment_amount == 0) {
            $salary->status = 'unpaid';
        } elseif ($due > 0) {
            $salary->status = 'partial_paid';
        } else {
            $salary->status = 'paid';
        }
    
        $ledger = Ledger::findOrFail($request->payment_method);
        $payment_method = $ledger->type;

        $salary->payment_method = $request->payment_method;
        $salary->ledger_id = $request->payment_method;
        $salary->payment_date = now();
        $salary->save();

        return redirect()->back()->with('success', 'Payment updated successfully!');
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
    public function destroy(string $id)
    {
        //
    }
}
