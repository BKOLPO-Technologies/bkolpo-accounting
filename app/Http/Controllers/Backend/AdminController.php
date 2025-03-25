<?php

namespace App\Http\Controllers\Backend;

use App\Models\Project;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function AdminDashboard(){

        $pageTitle = 'Admin Dashboard';

        // Project Calculations
        $projectTotalAmount = Project::sum('grand_total');
        $projectTotalAmountPaid = Project::sum('paid_amount');
        $projectTotalAmountDue = $projectTotalAmount - $projectTotalAmountPaid;
        $purchaseTotalAmount = Purchase::sum('total');

        // Month-wise Purchases
        // $monthlyPurchases = Purchase::select(
        //         DB::raw('SUM(total) as total'),
        //         DB::raw('DATE_FORMAT(created_at, "%M") as month')
        //     )
        //     ->groupBy('month')
        //     ->orderBy(DB::raw('MIN(created_at)'), 'ASC')
        //     ->pluck('total', 'month'); // Get as key-value pair (Month => Total)

        $monthlyPurchases = Purchase::select(
            DB::raw('SUM(total) as total'),
            DB::raw('DATE_FORMAT(created_at, "%M") as month')
        )
        ->groupBy('month')
        ->orderBy(DB::raw('MIN(created_at)'), 'ASC')
        ->pluck('total', 'month')
        ->toArray();  // Convert Collection to array
    

            // dd($monthlyPurchases);

        return view('dashboard', compact(
            'pageTitle', 
            'projectTotalAmount', 
            'projectTotalAmountPaid', 
            'projectTotalAmountDue', 
            'purchaseTotalAmount', 
            'monthlyPurchases'
        ));

    }

    public function AdminDestroy(Request $request){

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success','Admin logout Successfully');
    }
}
