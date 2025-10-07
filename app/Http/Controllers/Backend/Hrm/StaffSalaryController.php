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

            // Validate input
            $request->validate([
                'month' => 'required|numeric|min:1|max:12',
                'year' => 'required|numeric',
                'staff_id' => 'required|array',
            ]);

            // Format salary month to YYYY-MM-01
            $salaryMonth = Carbon::createFromDate($request->year, $request->month, 1)->toDateString(); // e.g., "2025-09-01"

            foreach ($request->staff_id as $index => $staffId) {
                // Fetch individual salary components or default to 0
                $basic = $request->basic_salary[$index] ?? 0;
                $hra = $request->hra[$index] ?? 0;
                $medical = $request->medical[$index] ?? 0;
                $conveyance = $request->conveyance[$index] ?? 0;
                $pf = $request->pf[$index] ?? 0;
                $tax = $request->tax[$index] ?? 0;
                $other = $request->other_deductions[$index] ?? 0;

                // Calculate gross and net salary
                $gross = $basic + $hra + $medical + $conveyance;
                $net = $gross - ($pf + $tax + $other);
                $paymentAmount = $request->payment_amount[$index] ?? $net;

                // Check if salary already exists for this staff and month
                $exists = StaffSalary::where('staff_id', $staffId)
                    ->where('salary_month', $salaryMonth)
                    ->exists();

                if ($exists) {
                    // Optional: log skipped record
                    continue; // Skip existing
                }

                // Create new salary entry
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
            }

            DB::commit();

            return redirect()->route('admin.staff.salary.index')
                ->with('success', 'Staff salary generated successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();

            // Log the error for debug (optional)
            \Log::error('Salary generation error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Error: ' . $e->getMessage());
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
