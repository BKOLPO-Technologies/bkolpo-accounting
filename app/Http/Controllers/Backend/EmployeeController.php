<?php

namespace App\Http\Controllers\Backend;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class EmployeeController extends Controller
{
    public function AdminEmployeeIndex() 
    {
        $employees = Employee::all();
        //dd($employees);
        $pageTitle = 'Admin Employee';
        return view('backend/admin/employee/index',compact('pageTitle', 'employees'));
    }

    public function AdminEmployeeAdd() 
    {
        $roles = DB::table('roles')->get();
        // dd($roles);
        $pageTitle = 'Admin Employee Add';
        return view('backend/admin/employee/add',compact('pageTitle', 'roles'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
            'username' => 'required|string',
            'email' => 'required|email|unique:employees,email',
            'password' => 'required|string|min:6',
            'role' => 'required|exists:roles,id',
        ]);
    
        Employee::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role_id' => $validated['role'],
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'region' => $request->region,
            'country' => $request->country,
            'postbox' => $request->postbox,
            'phone' => $request->phone,
        ]);

        return redirect()->route('admin.employee.index')->with('success', 'Employee added successfully.');
    }

}
