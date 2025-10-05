<?php

namespace App\Http\Controllers\Backend\Hrm;

use App\Http\Controllers\Controller;
use App\Models\Ledger;
use App\Models\Staff;
use App\Models\StaffSalary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class StaffSalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = StaffSalary::with('staff');

        if ($request->month && $request->year) {
            $salaryMonth = $request->year . '-' . str_pad($request->month, 2, '0', STR_PAD_LEFT) . '-01';
            $query->where('salary_month', $salaryMonth);
        }

        $salaries = $query->latest()->paginate(20); // pagination optional
        $ledgers = Ledger::whereIn('type', ['Bank', 'Cash'])->get();
        return view('backend.admin.hrm.salary.index', compact('salaries','ledgers'));
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

        // Active staff যারা ঐ মাসের salary পাননি
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
            // Validation
            $request->validate([
                'month' => 'required|integer|min:1|max:12',
                'year' => 'required|integer|min:2000',
                'staff_id' => 'required|array',
                'staff_id.*' => 'exists:staff,id',
                'basic_salary' => 'required|array',
                'payment_method' => 'required',
                'payment_amount' => 'required|array', // Add validation for payment_amount
            ]);

            // Salary Month (Year + Month → Date)
            $salaryMonth = $request->year.'-'.str_pad($request->month, 2, '0', STR_PAD_LEFT).'-01';

            foreach ($request->staff_id as $key => $staffId) {
                $basic = $request->basic_salary[$key] ?? 0;
                $hra = $request->hra[$key] ?? 0;
                $medical = $request->medical[$key] ?? 0;
                $conveyance = $request->conveyance[$key] ?? 0;
                $pf = $request->pf[$key] ?? 0;
                $tax = $request->tax[$key] ?? 0;
                $otherDeduct = $request->other_deductions[$key] ?? 0;

                $gross = $basic + $hra + $medical + $conveyance;
                $deductions = $pf + $tax + $otherDeduct;
                $net = $gross - $deductions;

                $ledger = Ledger::findOrFail($request->payment_method);
                $payment_method = $ledger->type; // Cash or Bank

                $paymentAmount = $request->payment_amount[$key] ?? 0;

                // Determine status based on payment amount
                if ($paymentAmount >= $net) {
                    $status = 'Paid';
                } elseif ($paymentAmount > 0 && $paymentAmount < $net) {
                    $status = 'partial_paid';
                } else {
                    $status = 'Pending';
                }

                StaffSalary::create([
                    'staff_id' => $staffId,
                    'ledger_id' => $request->payment_method,
                    'salary_month' => $salaryMonth,
                    'basic_salary' => $basic,
                    'hra' => $hra,
                    'medical' => $medical,
                    'conveyance' => $conveyance,
                    'pf' => $pf,
                    'tax' => $tax,
                    'other_deductions' => $otherDeduct,
                    'gross_salary' => $gross,
                    'net_salary' => $net,
                    'payment_method' => $payment_method,
                    'payment_amount' => $paymentAmount, // Save paid amount
                    'status' => $status, // Dynamic status
                ]);
            }

            return redirect()->route('admin.staff.salary.index')
                ->with('success', 'Staff salaries created successfully!');

        } catch (\Exception $e) {
            // \Log::error('Salary Store Error: '.$e->getMessage());
            return back()->with('error', 'Something went wrong while creating salaries. Please try again.');
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
        $salary->delete();

        return redirect()->route('admin.staff.salary.index')
                        ->with('success', 'Salary deleted successfully!');
    }

}
