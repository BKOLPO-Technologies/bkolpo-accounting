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
                        <li class="breadcrumb-item active">View</li>
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

                        <!-- <h5>Project Details</h5> -->

                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Project Details</h5>
                            <a href="{{ route('admin.project')}}" class="btn btn-sm btn-danger rounded-0">
                                <i class="fa-solid fa-arrow-left"></i> Back To List
                            </a>
                        </div>

                        <hr>

                        <div class="row mb-3 ml-3">
                            <label class="col-sm-4 font-weight-bold">Title:</label>
                            <div class="col-sm-8">
                                <p>{{ $project->title }}</p>
                            </div>
                        </div>

                        <hr/>

                        <div class="row mb-3 ml-3">
                            <label class="col-sm-4 font-weight-bold">Status:</label>
                            <div class="col-sm-8">
                                <p>{{ $project->status }}</p>
                            </div>
                        </div>

                        <hr/>

                        <div class="row mb-3 ml-3">
                            <label class="col-sm-4 font-weight-bold">Priority:</label>
                            <div class="col-sm-8">
                                <p>{{ $project->priority }}</p>
                            </div>
                        </div>

                        <hr/>

                        <div class="row mb-3 ml-3">
                            <label class="col-sm-4 font-weight-bold">Customer:</label>
                            <div class="col-sm-8">
                                <p>{{ $project->customer->name }}</p>
                            </div>
                        </div>

                        <hr/>

                        <div class="row mb-3 ml-3">
                            <label class="col-sm-4 font-weight-bold">Customer Can View:</label>
                            <div class="col-sm-8">
                                <p>{{ $project->customerview ? 'True' : 'False' }}</p>
                            </div>
                        </div>

                        <hr/>

                        <div class="row mb-3 ml-3">
                            <label class="col-sm-4 font-weight-bold">Customer Can Comment:</label>
                            <div class="col-sm-8">
                                <p>{{ $project->customercomment ? 'True' : 'False' }}</p>
                            </div>
                        </div>

                        <hr/>

                        <div class="row mb-3 ml-3">
                            <label class="col-sm-4 font-weight-bold">Budget:</label>
                            <div class="col-sm-8">
                                <p>{{ $project->budget }}</p>
                            </div>
                        </div>

                        <hr/>

                        

                        <div class="row mb-3 ml-3">
                            <label class="col-sm-4 font-weight-bold">Phase:</label>
                            <div class="col-sm-8">
                                <p>{{ $project->phase }}</p>
                            </div>
                        </div>

                        <hr/>

                        <div class="row mb-3 ml-3">
                            <label class="col-sm-4 font-weight-bold">Start Date:</label>
                            <div class="col-sm-8">
                                <p>{{ $project->start_date }}</p>
                            </div>
                        </div>

                        <hr/>

                        <div class="row mb-3 ml-3">
                            <label class="col-sm-4 font-weight-bold">Due Date:</label>
                            <div class="col-sm-8">
                                <p>{{ $project->end_date }}</p>
                            </div>
                        </div>

                        <hr/>

                        <div class="row mb-3 ml-3">
                            <label class="col-sm-4 font-weight-bold">Link to Calendar:</label>
                            <div class="col-sm-8">
                                <p>
                                    @if($project->link_to_calendar == 0)
                                        No
                                    @elseif($project->link_to_calendar == 1)
                                        Mark Deadline (End Date)
                                    @else
                                        Mark Start to End Date
                                    @endif
                                </p>
                            </div>
                        </div>

                        <hr/>

                        <div class="row mb-3 ml-3">
                            <label class="col-sm-4 font-weight-bold">Note:</label>
                            <div class="col-sm-8">
                                <p>{!! $project->note !!}</p>
                            </div>
                        </div>

                        <hr/>

                        <div class="row mb-3 ml-3">
                            <label class="col-sm-4 font-weight-bold">Tags:</label>
                            <div class="col-sm-8">
                                <p>{{ $project->tags }}</p>
                            </div>
                        </div>

                        <hr/>

                        <div class="row mb-3 ml-3">
                            <label class="col-sm-4 font-weight-bold">Task Communication:</label>
                            <div class="col-sm-8">
                                <p>
                                    @if($project->task_communication == 0)
                                        No
                                    @elseif($project->task_communication == 1)
                                        Emails to Team
                                    @else
                                        Emails to Team, Customer
                                    @endif
                                </p>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-sm-12">
                                <a href="{{ route('admin.project') }}" class="btn btn-primary">Back to Projects</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection