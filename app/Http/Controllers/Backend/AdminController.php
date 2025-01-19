<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class AdminController extends Controller
{
    public function AdminDashboard(){

        $pageTitle = 'Admin Dashboard';
        return view('dashboard',compact('pageTitle'));

    }

    public function AdminDestroy(Request $request){

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success','Admin logout Successfully');
    }
}
