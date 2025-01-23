@extends('layouts.admin')
@section('admin')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"></div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: black;">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.project') }}" style="text-decoration: none; color: black;">Project</a></li>
                        <li class="breadcrumb-item active">Edit</li>
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
                        <form method="POST" action="{{ route('admin.projectUpdate', $project->id) }}" id="data_form" class="form-horizontal">
                            @csrf
                            @method('PUT')

                            <h5>Edit Project</h5>
                            <hr>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="name">Title</label>
                                <div class="col-sm-10">
                                    <input type="text" placeholder="Project Title"
                                        class="form-control margin-bottom required" name="name" value="{{ $project->title }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="status">Status</label>
                                <div class="col-sm-4">
                                    <select name="status" class="form-control">
                                        <option value="Waiting" {{ $project->status == 'Waiting' ? 'selected' : '' }}>Waiting</option>
                                        <option value="Pending" {{ $project->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Terminated" {{ $project->status == 'Terminated' ? 'selected' : '' }}>Terminated</option>
                                        <option value="Finished" {{ $project->status == 'Finished' ? 'selected' : '' }}>Finished</option>
                                        <option value="Progress" {{ $project->status == 'Progress' ? 'selected' : '' }}>In Progress</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="priority">Priority</label>
                                <div class="col-sm-4">
                                    <select name="priority" class="form-control">
                                        <option value="Low" {{ $project->priority == 'Low' ? 'selected' : '' }}>Low</option>
                                        <option value="Medium" {{ $project->priority == 'Medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="High" {{ $project->priority == 'High' ? 'selected' : '' }}>High</option>
                                        <option value="Urgent" {{ $project->priority == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="customer">Customer</label>
                                <div class="col-sm-10">
                                    <select name="customer" class="form-control" id="customer_statement">
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ $project->customer_id == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"
                                    for="name">Customer Can View</label>

                                <div class="col-sm-4">
                                    <select name="customerview" class="form-control">
                                        <option value="1" {{ $project->customerview == '1' ? 'selected' : '' }}>True</option>
                                        <option value="0" {{ $project->customerview == '0' ? 'selected' : '' }}>False</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"
                                    for="name">Customer Can Comment</label>

                                <div class="col-sm-4">
                                    <select name="customercomment" class="form-control">
                                        <option value="1" {{ $project->customercomment == '1' ? 'selected' : '' }}>True</option>
                                        <option value="0" {{ $project->customercomment == '0' ? 'selected' : '' }}>False</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="worth">Budget</label>
                                <div class="col-sm-4">
                                    <input type="number" placeholder="Budget" class="form-control margin-bottom required" name="worth" value="{{ $project->budget }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="pay_cat">Assign to</label>

                                <div class="col-sm-8">
                                    <select name="employee[]" class="form-control required select-box" multiple="multiple">
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" 
                                                {{ in_array($employee->id, $assignedEmployees) ? 'selected' : '' }}>
                                                {{ $employee->username }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="phase">Phase</label>

                                <div class="col-sm-10">
                                    <input type="text" placeholder="Phase A,B,C"
                                        class="form-control margin-bottom  required" name="phase" value="{{ $project->phase }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="sdate">Start Date</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control required" placeholder="Start Date" name="sdate" value="{{ $project->start_date }}" autocomplete="false">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="edate">Due Date</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control required" placeholder="End Date" name="edate" value="{{ $project->end_date }}" autocomplete="false">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"
                                    for="name">Link to calendar</label>

                                <div class="col-sm-4">
                                    <select name="link_to_cal" class="form-control" id="link_to_cal">
                                        <option value='0' {{ $project->link_to_calendar == '0' ? 'selected' : '' }}>No</option>
                                        <option value='1' {{ $project->link_to_calendar == '1' ? 'selected' : '' }}>Mark Deadline(End Date)</option>
                                        <option value='2' {{ $project->link_to_calendar == '2' ? 'selected' : '' }}>Mark Start to End Date</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Note</label>
                                <div class="col-sm-10">
                                    <textarea class="summernote" id="content" name="content">{{ $project->note }}</textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="tags">Tags</label>

                                <div class="col-sm-10">
                                    <input type="text" placeholder="Tags"
                                        class="form-control margin-bottom  required" name="tags" value="{{ $project->tags }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"
                                    for="name">Task Communication</label>

                                <div class="col-sm-4">
                                    <select name="ptype" class="form-control">
                                        <option value='0' {{ $project->task_communication == '0' ? 'selected' : '' }}>No</option>
                                        <option value='1' {{ $project->task_communication == '1' ? 'selected' : '' }}>Emails to team</option>
                                        <option value='2' {{ $project->task_communication == '2' ? 'selected' : '' }}>Emails to team, customer</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label"></label>
                                <div class="col-sm-4">
                                    <input type="submit" id="submit-data" class="btn btn-primary margin-bottom" value="Update" data-loading-text="Updating...">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('.select-box').select2();
        $('.summernote').summernote({
            height: 250,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['fullscreen', ['fullscreen']],
                ['codeview', ['codeview']]
            ]
        });
    });
</script>

@endsection
