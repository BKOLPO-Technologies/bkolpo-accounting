<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\ExpenseCategory;
use App\Models\Journal;
use App\Models\Transaction;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Str;

class JournalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Journal Voucher List';

        $vouchers = Transaction::with('journals')->where('status',1)->latest()->get();
        return view('backend.admin.voucher.journal.index',compact('pageTitle','vouchers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageTitle = 'Journal Voucher Create';
        $branches = ExpenseCategory::where('status',1)->latest()->get();
        $ledgers = ExpenseCategory::where('status',1)->latest()->get();

        // Generate a random unique transaction code
        $transactionCode = 'TX' . strtoupper(Str::random(8));

        return view('backend.admin.voucher.journal.create',compact('pageTitle','branches','ledgers','transactionCode'));
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
