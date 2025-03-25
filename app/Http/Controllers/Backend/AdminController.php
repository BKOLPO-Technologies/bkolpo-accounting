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
        $purchaseTotalAmount = Purchase::sum('total');

        // Get purchase data month-wise
        $purchases = Purchase::select(
            DB::raw('SUM(total) as total'),
            DB::raw('MONTH(created_at) as month')
        )
        ->groupBy('month')
        ->orderBy('month', 'ASC')
        ->pluck('total', 'month')
        ->toArray();

        // Ensure all 12 months are present
        $allMonths = [];
        for ($i = 1; $i <= 12; $i++) {
            $allMonths[Carbon::create()->month($i)->format('F')] = $purchases[$i] ?? 0;
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
