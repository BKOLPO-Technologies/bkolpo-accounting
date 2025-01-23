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
                    <!-- 
                    <div class="card-header">
                        <h3 class="card-title">DataTable with minimal features & hover style</h3>
                    </div> 
                    -->
                    <!-- /.card-header -->
                    <div class="card-body">

                        <!-- -------------- -->
                        <article class="content">
                            
                            <div id="notify" class="alert alert-success" style="display:none;">
                                <a href="#" class="close" data-dismiss="alert">&times;</a>

                                <div class="message"></div>
                            </div>
                            <div class="grid_3 grid_4">
                                <div class="header-block">
                                    <h3 class="title">
                                        Projects <a href="{{ route('admin.projectCreate') }}" class="btn btn-primary btn-sm rounded">
                                            Add new </a>
                                    </h3>
                                </div>
                                <p>&nbsp;</p>
                                <div class="row">
                                    <div class="col-xl-3 col-lg-6 col-xs-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="card-block">
                                                    <div class="media">
                                                        <div class="media-body text-xs-left">
                                                            <h3 class="pink" id="dash_0">{{ $countWaitingProject }}</h3>
                                                            <span>Waiting</span>
                                                        </div>
                                                        <style>
                                                            .pink {
                                                                color:rgb(255, 0, 0);
                                                            }

                                                            .font-large-2 {
                                                                font-size: 3rem;
                                                            }

                                                            .float-xs-right {
                                                                float: right;
                                                            }
                                                        </style>
                                                        <div class="media-right media-middle">
                                                            <i class="fa-regular fa-clock pink font-large-2 float-xs-right"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-6 col-xs-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="card-block">
                                                    <div class="media">
                                                        <div class="media-body text-xs-left">
                                                            <h3 class="indigo" id="dash_1">{{ $countProgressProject }}</h3>
                                                            <span>In Progress</span>
                                                        </div>
                                                        <style>
                                                            .indigo {
                                                                color:rgb(0, 38, 255);
                                                            }
                                                        </style>
                                                        <div class="media-right media-middle">
                                                            <i class="fa-solid fa-circle-notch indigo font-large-2 float-xs-right"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-6 col-xs-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="card-block">
                                                    <div class="media">
                                                        <div class="media-body text-xs-left">
                                                            <h3 class="green" id="dash_2">0</h3>
                                                            <span>Finished</span>
                                                        </div>
                                                        <style>
                                                            .green {
                                                                color:rgb(16, 187, 0);
                                                            }
                                                        </style>
                                                        <div class="media-right media-middle">
                                                            <i class="fa-regular fa-circle-check green font-large-2 float-xs-right"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-6 col-xs-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="card-block">
                                                    <div class="media">
                                                        <div class="media-body text-xs-left">
                                                            <h3 class="deep-cyan" id="dash_6">{{ $totalProject }}</h3>
                                                            <span>Total</span>
                                                        </div>
                                                        <style>
                                                            .deep-cyan {
                                                                color:rgb(59, 59, 59);
                                                            }
                                                        </style>
                                                        <div class="media-right media-middle">
                                                            <i class="fa-solid fa-signal deep-cyan font-large-2 float-xs-right"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                
                            </div>
                            
                            <input type="hidden" id="dashurl" value="projects/projects_stats">
                        </article>
                        <!-- -------------- -->

                        <br>

                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Project</th>
                                    <th>Due Date</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Settings</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($projects as $project)
                                <tr>
                                    <td>1001</td>
                                    <td>{{ $project->title }}</td>
                                    <td>{{ $project->end_date }}</td>
                                    <td>{{ $project->customer->name }}</td>
                                    <td>{{ $project->status }}</td>
                                    <td>
                                        <a type="button" class="btn btn-primary" href="#">
                                            <i class="fa-regular fa-file-lines" style="margin-right: 8px;"></i>View
                                        </a>

                                        <!-- <button type="submit" class="btn btn-info"><i class="fa-solid fa-pen-to-square"></i></button> -->

                                        <a href="{{ route('admin.projectEdit', $project->id) }}" class="btn btn-info">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>

                                        <form action="{{ route('admin.projectDelete', $project->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this project?')">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </section>

</div>

@endsection