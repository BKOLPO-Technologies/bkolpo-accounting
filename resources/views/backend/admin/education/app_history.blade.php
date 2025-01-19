@extends('layouts.admin')
@section('admin')
    <main class="app-main">
        <div class="app-content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h3>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-end">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ $pageTitle ?? 'N/A' }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                <div class="card card-primary card-outline mb-4">
                    <div class="card-header">
                        <div class="card-title">{{ $pageTitle ?? '' }}</div>
                    </div>

                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3 d-flex align-items-center">
                                <label for="itemsPerPage" class="me-2">Items per page:</label>
                                <select class="form-select" id="itemsPerPage" aria-label="Items per page">
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            <div class="col-md-7"></div> <!-- Spacer column -->
                            <div class="col-md-2">
                                <input type="text" class="form-control" id="searchInput" placeholder="Search courses..." onkeyup="searchCourses()">
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Course</th>
                                        <th>Live Session</th>
                                        <th>Session Duration</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Meeting Duration</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Course Title 1</td>
                                        <td><span class="badge bg-success">Yes</span></td>
                                        <td>1 hour</td>
                                        <td>01 Jan 2021</td>
                                        <td>01 Jan 2021</td>
                                        <td>1 hour</td>
                                        <td>
                                            <a href="#" class="btn btn-success btn-sm me-2" title="Edit">
                                                <i class="fa-solid fa-pencil"></i>
                                            </a>
                                            <a href="#" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="fa-solid fa-trash"></i> 
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Course Title 2</td>
                                        <td><span class="badge bg-danger">No</span></td>
                                        <td>N/A</td>
                                        <td>15 Mar 2021</td>
                                        <td>15 Mar 2021</td>
                                        <td>N/A</td>
                                        <td>
                                            <a href="#" class="btn btn-success btn-sm me-2" title="Edit">
                                                <i class="fa-solid fa-pencil"></i>
                                            </a>
                                            <a href="#" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="fa-solid fa-trash"></i> 
                                            </a>
                                        </td>
                                    </tr>
                                    <!-- Add more rows as needed -->
                                </tbody>
                            </table>
                        </div>

                        <div class="pagination mt-3">
                            <nav>
                                <ul class="pagination justify-content-end">
                                    <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </main>
@endsection
