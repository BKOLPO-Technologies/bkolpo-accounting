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
        $projects = Project::with('customer')->get();
        // dd($projects);

        // Count projects based on their status
        $countPendingProject = Project::where('status', 'Pending')->count();
        $countProgressProject = Project::where('status', 'Progress')->count();
        $countFinishedProject = Project::where('status', 'Finished')->count();
        $countTerminatedProject = Project::where('status', 'Terminated')->count();
        $countWaitingProject = Project::where('status', 'Waiting')->count();
        $totalProject = Project::count();

        $pageTitle = 'Admin Project';

        return view('backend.admin.project.index',compact(
            'pageTitle', 
            'projects',
            'countPendingProject', 
            'countProgressProject', 
            'countFinishedProject', 
            'countTerminatedProject', 
            'countWaitingProject',
            'totalProject'
        ));

    }

    public function AdminProjectCreate() 
    {
        $employees = Employee::all();
        $customers = Customer::all();
        $pageTitle = 'Admin Project Create';
        return view('backend.admin.project.create',compact('pageTitle', 'customers', 'employees'));

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

    public function destroy($id)
    {
        try {
            $project = Project::findOrFail($id); // Find the project
            $project->delete(); // Delete the project
            
            return redirect()->route('admin.project')->with('success', 'Project deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.project')->with('error', 'Failed to delete the project.');
        }
    }

    public function view($id)
    {
        $employees = Employee::all();
        $customers = Customer::all();
        $project = Project::findOrFail($id);

        $assignedEmployees = $project->employees->pluck('id')->toArray();

        //dd($assignedEmployees);

        return view('backend.admin.project.view', compact('project', 'customers', 'employees', 'assignedEmployees')); // Return the edit view
    }

    public function edit($id)
    {
        $employees = Employee::all();
        $customers = Customer::all();
        $project = Project::findOrFail($id); // Find the project by ID

        $assignedEmployees = $project->employees->pluck('id')->toArray();

        //dd($assignedEmployees);

        return view('backend.admin.project.edit', compact('project', 'customers', 'employees', 'assignedEmployees')); // Return the edit view
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|string|in:Waiting,Pending,Terminated,Finished,Progress',
            'priority' => 'required|string|in:Low,Medium,High,Urgent',
            'customer' => 'required|exists:customers,id',
            'customerview' => 'required|boolean',
            'customercomment' => 'required|boolean',
            'worth' => 'nullable|numeric',
            'phase' => 'nullable|string|max:255',
            'sdate' => 'nullable|date',
            'edate' => 'nullable|date|after_or_equal:sdate',
            'link_to_cal' => 'nullable|integer|in:0,1,2',
            'content' => 'nullable|string',
            'tags' => 'nullable|string|max:255',
            'ptype' => 'required|integer|in:0,1,2',
            'employee' => 'required|array',
            'employee.*' => 'exists:employees,id',
        ]);

        try {
            $project = Project::findOrFail($id);

            // Update basic project fields
            $project->update([
                'title' => $request->name,
                'status' => $request->status,
                'priority' => $request->priority,
                'customer_id' => $request->customer,
                'customerview' => $request->customerview,
                'customercomment' => $request->customercomment,
                'budget' => $request->worth,
                'phase' => $request->phase,
                'start_date' => $request->sdate,
                'end_date' => $request->edate,
                'link_to_calendar' => $request->link_to_cal,
                'note' => $request->content,
                'tags' => $request->tags,
                'task_communication' => $request->ptype,
            ]);

            // Sync employees
            $project->employees()->sync($request->employee);

            return redirect()->route('admin.project')->with('success', 'Project updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.project')->with('error', 'Failed to update the project. Please try again.');
        }
    }



}
