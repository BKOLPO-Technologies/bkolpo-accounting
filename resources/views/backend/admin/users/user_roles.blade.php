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
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
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
                                        <th>S.No</th>
                                        <th>Title</th>
                                        <th>Users Count</th>
                                        <th>Admin Panel Access</th>
                                        <th>Created Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Course Title 1</td>
                                        <td><span class="badge bg-primary">150</span></td>
                                        <td><span class="badge bg-success">Yes</span></td>
                                        <td>01 Jan 2024</td>
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
                                        <td>2</td>
                                        <td>Course Title 2</td>
                                        <td><span class="badge bg-primary">100</span></td>
                                        <td><span class="badge bg-danger">No</span></td>
                                        <td>15 Mar 2024</td>
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
                                        <td>3</td>
                                        <td>Course Title 3</td>
                                        <td><span class="badge bg-primary">80</span></td>
                                        <td><span class="badge bg-success">Yes</span></td>
                                        <td>10 Apr 2024</td>
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
                                        <td>4</td>
                                        <td>Course Title 4</td>
                                        <td><span class="badge bg-primary">90</span></td>
                                        <td><span class="badge bg-danger">No</span></td>
                                        <td>12 May 2024</td>
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
                                        <td>5</td>
                                        <td>Course Title 5</td>
                                        <td><span class="badge bg-primary">200</span></td>
                                        <td><span class="badge bg-success">Yes</span></td>
                                        <td>01 Jul 2024</td>
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
                                        <td>6</td>
                                        <td>Course Title 6</td>
                                        <td><span class="badge bg-primary">110</span></td>
                                        <td><span class="badge bg-success">Yes</span></td>
                                        <td>22 Sep 2024</td>
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
                                        <td>7</td>
                                        <td>Course Title 7</td>
                                        <td><span class="badge bg-primary">75</span></td>
                                        <td><span class="badge bg-danger">No</span></td>
                                        <td>18 Oct 2024</td>
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
                                        <td>8</td>
                                        <td>Course Title 8</td>
                                        <td><span class="badge bg-primary">250</span></td>
                                        <td><span class="badge bg-success">Yes</span></td>
                                        <td>01 Dec 2024</td>
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
                                        <td>9</td>
                                        <td>Course Title 9</td>
                                        <td><span class="badge bg-primary">130</span></td>
                                        <td><span class="badge bg-danger">No</span></td>
                                        <td>15 Jan 2024</td>
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
                                        <td>10</td>
                                        <td>Course Title 10</td>
                                        <td><span class="badge bg-primary">40</span></td>
                                        <td><span class="badge bg-success">Yes</span></td>
                                        <td>20 Feb 2024</td>
                                        <td>
                                            <a href="#" class="btn btn-success btn-sm me-2" title="Edit">
                                                <i class="fa-solid fa-pencil"></i>
                                            </a>
                                            <a href="#" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="fa-solid fa-trash"></i> 
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
        
                        <div class="pagination mt-3">
                            <nav>
                                <ul class="pagination justify-content-end">
                                    <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
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
