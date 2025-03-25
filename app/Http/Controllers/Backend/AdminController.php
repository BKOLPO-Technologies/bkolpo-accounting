<?php

namespace App\Http\Controllers\Backend;

use App\Models\Project;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function AdminDashboard(){

        $pageTitle = 'Admin Dashboard';

        $projectTotalAmount = Project::sum('grand_total');
        $projectTotalAmountPaid = Project::sum('paid_amount');
        $projectTotalAmountDue = $projectTotalAmount - $projectTotalAmountPaid;
        $purchaseTotalAmount = Purchase::sum('total');

        return view('dashboard',compact('pageTitle', 'projectTotalAmount', 'projectTotalAmountPaid', 'projectTotalAmountDue', 'purchaseTotalAmount'));

    }

    public function AdminDestroy(Request $request){

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success','Admin logout Successfully');
    }
}
