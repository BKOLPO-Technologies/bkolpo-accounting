<?php

namespace App\Http\Controllers\Backend;

use App\Models\Project;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function AdminDashboard(){

        $pageTitle = 'Admin Dashboard';

        $projectTotalAmount = Project::sum('grand_total');
        $projectTotalAmountPaid = Project::sum('paid_amount');
        $projectTotalAmountDue = $projectTotalAmount - $projectTotalAmountPaid;
        $purchaseTotalAmount = Project::sum('grand_total');

        // Get projects data month-wise
        $projects = Project::select(
            DB::raw('SUM(grand_total) as grand_total'),
            DB::raw('MONTH(created_at) as month')
        )
        ->groupBy('month')
        ->orderBy('month', 'ASC')
        ->pluck('grand_total', 'month')
        ->toArray();

        // Ensure all 12 months are present
        $allMonths = [];
        for ($i = 1; $i <= 12; $i++) {
            $allMonths[Carbon::create()->month($i)->format('F')] = $projects[$i] ?? 0;
        }

        return view('dashboard', compact(
            'pageTitle',
            'projectTotalAmount',
            'projectTotalAmountPaid',
            'projectTotalAmountDue',
            'purchaseTotalAmount',
            'allMonths'
        ));

    }

    public function AdminDestroy(Request $request){

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success','Admin logout Successfully');
    }
}
