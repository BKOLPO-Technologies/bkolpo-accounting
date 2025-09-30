<?php

namespace App\Http\Controllers\Backend\Hrm;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Staff;
use App\Models\User;
use App\Models\Hrm\Education;
use App\Models\Hrm\Certification;
use App\Models\Hrm\Award;
use App\Models\Hrm\EmploymentHistory;
use App\Models\Hrm\StaffDocument;
use Illuminate\Support\Facades\Storage;
use Exception;
use DB;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Staff list';
        $staffs = Staff::latest()->get();
        return view('backend.admin.hrm.staff.index',compact('pageTitle','staffs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Staff Create';
        return view('backend.admin.hrm.staff.create',compact('pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|unique:staff,employee_id',
            'join_date'   => 'required|date',
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:staff,email',
            'phone'       => 'nullable|string|max:20',
            'department'  => 'required|string',
            'designation' => 'required|string',
            'salary'      => 'required|numeric|min:0',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cv'            => 'required|mimes:pdf|max:5120', // PDF Allow
            'address'       => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $exists = Staff::where('employee_id', $request->employee_id)->first();
            if ($exists) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['employee_id' => 'This Employee ID already exists!']);
            }
            $staff = new Staff();
            $staff->employee_id = $request->employee_id;
            $staff->join_date   = $request->join_date;
            $staff->name        = $request->name;
            $staff->email       = $request->email;
            $staff->phone       = $request->phone;
            $staff->department  = $request->department;
            $staff->designation = $request->designation;
            $staff->salary      = $request->salary;
            $staff->address     = $request->address;

            // Profile Image Upload
            if ($request->hasFile('profile_image')) {
                $imageName = time() . '.' . $request->profile_image->extension();
                $request->profile_image->move(public_path('upload/staff/profile'), $imageName);
                $staff->profile_image = 'upload/staff/profile/' . $imageName;
            }

            // CV Upload (only PDF)
            if ($request->hasFile('cv')) {
                $cvName = time() . '.' . $request->cv->extension();
                $request->cv->move(public_path('upload/staff/cv'), $cvName);
                $staff->cv = 'upload/staff/cv/' . $cvName;
            }

            $staff->save();

            DB::commit();

            return redirect()->route('admin.staff.index')
                ->with('success', 'Staff added successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error storing staff: ' . $e->getMessage());

            return redirect()->back()
                ->withInput() // old value গুলো রাখবে
                ->with('error', 'Failed to add staff! ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $staff = Staff::findOrFail($id);
        $pageTitle = 'Staff View';

        return view('backend.admin.hrm.staff.show',compact('staff','pageTitle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $staff = Staff::findOrFail($id);
        $pageTitle = 'Staff Edit';

        return view('backend.admin.hrm.staff.edit',compact('staff','pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required|unique:staff,employee_id,' . $id,
            'join_date'   => 'required|date',
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:staff,email,' . $id,
            'phone'       => 'nullable|string|max:20',
            'department'  => 'required|string',
            'designation' => 'required|string',
            'salary'      => 'required|numeric|min:0',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cv'            => 'nullable|mimes:pdf|max:5120',
            'address'       => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $staff = Staff::findOrFail($id);
            $staff->employee_id = $request->employee_id;
            $staff->join_date   = $request->join_date;
            $staff->name        = $request->name;
            $staff->email       = $request->email;
            $staff->phone       = $request->phone;
            $staff->department  = $request->department;
            $staff->designation = $request->designation;
            $staff->salary      = $request->salary;
            $staff->address     = $request->address;
            $staff->status = $request->status;

            // Profile Image Upload
            if ($request->hasFile('profile_image')) {
                // Delete old image
                if ($staff->profile_image && file_exists(public_path($staff->profile_image))) {
                    unlink(public_path($staff->profile_image));
                }
                $imageName = time() . '.' . $request->profile_image->extension();
                $request->profile_image->move(public_path('upload/staff/profile'), $imageName);
                $staff->profile_image = 'upload/staff/profile/' . $imageName;
            }

            // CV Upload
            if ($request->hasFile('cv')) {
                // Delete old CV
                if ($staff->cv && file_exists(public_path($staff->cv))) {
                    unlink(public_path($staff->cv));
                }
                $cvName = time() . '.' . $request->cv->extension();
                $request->cv->move(public_path('upload/staff/cv'), $cvName);
                $staff->cv = 'upload/staff/cv/' . $cvName;
            }

            $staff->save();
            DB::commit();

            return redirect()->route('admin.staff.index')
                ->with('success', 'Staff updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating staff: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to update staff! ' . $e->getMessage());
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $staff = Staff::findOrFail($id);

            // Delete profile image if exists
            if ($staff->profile_image && file_exists(public_path($staff->profile_image))) {
                unlink(public_path($staff->profile_image));
            }

            // Delete CV if exists
            if ($staff->cv && file_exists(public_path($staff->cv))) {
                unlink(public_path($staff->cv));
            }

            $staff->delete();

            return redirect()->route('admin.staff.index')->with('success', 'Staff deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Error deleting staff: ' . $e->getMessage());
            return redirect()->route('admin.staff.index')->with('error', 'Failed to delete staff.');
        }
    }

}
