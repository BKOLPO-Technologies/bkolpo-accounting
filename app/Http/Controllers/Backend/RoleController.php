<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $roles = Role::orderBy('id','DESC')->latest()->get();
        $pageTitle = 'Role List';
        return view('backend.admin.roles.index',compact('roles','pageTitle'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $permission = Permission::get();
        $pageTitle = 'Role Create';
        return view('backend.admin.roles.create',compact('permission','pageTitle'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        // Manually validate the request using Validator facade
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
            'permission' => 'required|array',
            'permission.*' => 'exists:permissions,id', // Ensure each permission exists
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Get the permissions array and cast each to an integer
        $permissionsID = array_map(
            function($value) { return (int)$value; },
            $request->input('permission')
        );

        // Create the new role
        $role = Role::create(['name' => $request->input('name')]);

        // Sync the selected permissions with the role
        $role->syncPermissions($permissionsID);

        // Redirect back with success message
        return redirect()->route('roles.index')
                         ->with('success', 'Role created successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();

        $pageTitle = 'Role View';
        return view('backend.admin.roles.show',compact('role','rolePermissions','pageTitle'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        $pageTitle = 'Role Edit';
        return view('backend.admin.roles.edit',compact('role','permission','rolePermissions','pageTitle'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        // Use the Validator facade to validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'permission' => 'required|array', // Ensure permission is an array
            'permission.*' => 'exists:permissions,id', // Ensure each permission ID exists in the permissions table
        ]);
    
        // If validation fails, redirect back with errors
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Find the role by ID
        $role = Role::find($id);
    
        // If role doesn't exist, redirect with an error message
        if (!$role) {
            return redirect()->route('roles.index')->with('error', 'Role not found.');
        }
    
        // Update the role's name
        $role->name = $request->input('name');
        $role->save();
    
        // Get the permission IDs from the request and cast them to integers
        $permissionsID = array_map(
            function($value) { return (int)$value; },
            $request->input('permission')
        );
    
        // Sync the permissions with the role
        $role->syncPermissions($permissionsID);
    
        // Redirect back with success message
        return redirect()->route('roles.index')
                         ->with('success', 'Role updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        DB::table("roles")->where('id',$id)->delete();
        return redirect()->back()->with('success','Role deleted successfully');
    }
}
