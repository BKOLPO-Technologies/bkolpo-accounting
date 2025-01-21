<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request): View
    {
        $users = User::latest()->get();
        $pageTitle = 'User List';
        return view('backend.admin.users.index',compact('users','pageTitle'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $roles = Role::where('name', '!=', 'Admin')->pluck('name', 'name')->all(); 
        $pageTitle = 'User Create';
        return view('backend.admin.users.create',compact('roles','pageTitle'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate the request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'roles' => 'required|array',
        ]);

        // dd($validatedData);
    
        // Hash the password
        $validatedData['password'] = Hash::make($validatedData['password']);
        
        // Use create to insert the user
        $user = User::create([
            'name' => $validatedData['name'], // Set the name
            'email' => $validatedData['email'], // Set the email
            'password' => $validatedData['password'],
            'show_password' => $request->password // Assuming you want to store this as well
        ]);
        
        // Assign roles to the user
        $user->syncRoles($validatedData['roles']); // Use syncRoles to update roles
    
        // Redirect with success message
        return redirect()->route('users.index')
                         ->with('success', 'User created successfully');
    }
    
    
    
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $user = User::find($id);
        $pageTitle = 'User View';
        return view('backend.admin.users.show',compact('user','pageTitle'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $user = User::find($id);
        $roles = Role::where('name', '!=', 'Admin')->pluck('name', 'name')->all(); 
        $userRole = $user->roles->pluck('name','name')->all();

        $pageTitle = 'User Edit';
    
        return view('backend.admin.users.edit',compact('user','roles','userRole','pageTitle'));
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
        // Validate the request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'roles' => 'required|array'
        ]);
    
        // Find the user
        $user = User::findOrFail($id);
    
        // Update the user's name and email
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
    
        // Check if a new password is provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->show_password = $request->password; // If you want to store the plain password
        }
    
        // Save the user
        $user->save();
    
        // Clear existing roles and assign new roles
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $user->assignRole($validatedData['roles']);
    
        // Redirect with success message
        return redirect()->route('users.index')
                         ->with('success', 'User updated successfully');
    }
    
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();
        return redirect()->back()->with('success','User deleted successfully');
    }
}
