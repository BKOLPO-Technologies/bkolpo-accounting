<?php

namespace App\Http\Controllers\Backend\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Client;
use App\Models\Project;
use App\Models\JournalVoucher;
use App\Models\JournalVoucherDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Project List';
    
        $projects = Project::latest()->get();
    
        return view('backend.admin.inventory.project.index', compact('pageTitle', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Project Create';

        $clients = Client::latest()->get();

        return view('backend.admin.inventory.project.create',compact('pageTitle','clients')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
