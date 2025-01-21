@extends('layouts.admin')
@section('admin')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- <h1>DataTables</h1> -->
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: black;">Home</a></li>
                        <li class="breadcrumb-item active">All</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="post" id="data_form" class="form-horizontal">

                            <h5>Add New Transaction</h5>

                            <hr>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="name">Search Payer (Optional)</label>

                                <div class="col-sm-4">
                                    <input type="text" placeholder="Project Title"
                                        class="form-control margin-bottom  required" name="name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="name">C/o *</label>

                                <div class="col-sm-4">
                                    <input type="text" placeholder="Project Title"
                                        class="form-control margin-bottom  required" name="name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="name">Account</label>

                                <div class="col-sm-4">
                                    <select name="status" class="form-control">
                                        <option value='Waiting'>Waiting</option>
                                        <option value='Pending'>Pending</option>
                                        <option value='Terminated'>Terminated</option>
                                        <option value='Finished'>Finished</option>
                                        <option value='Progress'>In Progress</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="name">Date</label>
                                <div class="col-sm-4">
                                    <input type="datetime-local" name="" id="" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="name">Amount</label>

                                <div class="col-sm-4">
                                    <input type="number" placeholder="Amount" class="form-control margin-bottom required" name="name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="pay_cat">Type</label>
                                <div class="col-sm-4">
                                    <select name="priority" class="form-control">
                                        <option value='Low'>Low</option>
                                        <option value='Medium'>Medium</option>
                                        <option value='High'>High</option>
                                        <option value='Urgent'>Urgent</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="pay_cat">Category</label>
                                <div class="col-sm-4">
                                    <select name="priority" class="form-control">
                                        <option value='Low'>Low</option>
                                        <option value='Medium'>Medium</option>
                                        <option value='High'>High</option>
                                        <option value='Urgent'>Urgent</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="pay_cat">Method</label>
                                <div class="col-sm-4">
                                    <select name="priority" class="form-control">
                                        <option value='Low'>Low</option>
                                        <option value='Medium'>Medium</option>
                                        <option value='High'>High</option>
                                        <option value='Urgent'>Urgent</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"></label>

                                <div class="col-sm-4">
                                    <input type="submit" id="submit-data" class="btn btn-success margin-bottom"
                                        value="Add" data-loading-text="Adding...">
                                    <input type="hidden" value="projects/addproject" id="action-url">

                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

    </section>

</div>

@endsection