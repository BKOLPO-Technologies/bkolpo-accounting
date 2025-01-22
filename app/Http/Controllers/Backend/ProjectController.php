<?php

namespace App\Http\Controllers\Backend;

use App\Models\Project;
use App\Models\Customer;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    public function AdminProjectIndex() 
    {
        $projects = Project::all();
        $pageTitle = 'Admin Project';
        return view('backend/admin/project/index',compact('pageTitle', 'projects'));

    }

    public function AdminProjectCreate() 
    {
        $employees = Employee::all();
        $customers = Customer::all();
        $pageTitle = 'Admin Project Create';
        return view('backend/admin/project/create',compact('pageTitle', 'customers', 'employees'));

    }

    public function store(Request $request)
    {
        //dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|string',
            'priority' => 'required|string',
            'customer' => 'required|exists:customers,id',
            'customerview' => 'required|boolean',
            'customercomment' => 'required|boolean',
            'worth' => 'nullable|numeric',
            'phase' => 'nullable|string',
            'sdate' => 'nullable|date',
            'edate' => 'nullable|date|after_or_equal:sdate',
            'link_to_cal' => 'nullable|integer',
            'color' => 'nullable|string',
            'content' => 'nullable|string',
            'tags' => 'nullable|string',
            'ptype' => 'required|integer',
            'employee' => 'required|array',
            'employee.*' => 'exists:employees,id',
        ]);

        $project = Project::create([
            'title' => $validated['name'],
            'status' => $validated['status'],
            'priority' => $validated['priority'],
            'customer_id' => $validated['customer'],
            'customerview' => $validated['customerview'],
            'customercomment' => $validated['customercomment'],
            'budget' => $validated['worth'],
            'phase' => $validated['phase'],
            'start_date' => $validated['sdate'],
            'end_date' => $validated['edate'],
            'link_to_calendar' => $validated['link_to_cal'],
            'color' => $validated['color'],
            'note' => $validated['content'],
            'tags' => $validated['tags'],
            'task_communication' => $validated['ptype'],
        ]);

        $project->employees()->sync($validated['employee']);

        return redirect()->route('admin.project')->with('success', 'Project added successfully!');
    }
}
