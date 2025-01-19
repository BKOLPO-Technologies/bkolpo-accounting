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
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Instructor</th>
                                        <th>Price</th>
                                        <th>Sales</th>
                                        <th>Income</th>
                                        <th>Students</th>
                                        <th>Created Date</th>
                                        <th>Updated Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Course Title 1</td>
                                        <td>John Doe</td>
                                        <td>$100</td>
                                        <td>20</td>
                                        <td>$2000</td>
                                        <td>150</td>
                                        <td>01 Jan 2021</td>
                                        <td>01 Feb 2021</td>
                                        <td><span class="badge bg-success">Active</span></td>
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
                                        <td>Jane Smith</td>
                                        <td>$150</td>
                                        <td>10</td>
                                        <td>$1500</td>
                                        <td>100</td>
                                        <td>15 Mar 2021</td>
                                        <td>20 Apr 2021</td>
                                        <td><span class="badge bg-danger">Inactive</span></td>
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
                                        <td>Emily Johnson</td>
                                        <td>$200</td>
                                        <td>5</td>
                                        <td>$1000</td>
                                        <td>80</td>
                                        <td>10 Apr 2021</td>
                                        <td>15 May 2021</td>
                                        <td><span class="badge bg-success">Active</span></td>
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
                                        <td>Michael Brown</td>
                                        <td>$250</td>
                                        <td>7</td>
                                        <td>$1750</td>
                                        <td>90</td>
                                        <td>12 May 2021</td>
                                        <td>20 Jun 2021</td>
                                        <td><span class="badge bg-danger">Inactive</span></td>
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
                                        <td>Sarah Wilson</td>
                                        <td>$300</td>
                                        <td>12</td>
                                        <td>$3600</td>
                                        <td>200</td>
                                        <td>01 Jul 2021</td>
                                        <td>15 Aug 2021</td>
                                        <td><span class="badge bg-success">Active</span></td>
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
                                        <td>David Green</td>
                                        <td>$120</td>
                                        <td>15</td>
                                        <td>$1800</td>
                                        <td>110</td>
                                        <td>22 Sep 2021</td>
                                        <td>05 Oct 2021</td>
                                        <td><span class="badge bg-success">Active</span></td>
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
                                        <td>Lisa Turner</td>
                                        <td>$400</td>
                                        <td>8</td>
                                        <td>$3200</td>
                                        <td>75</td>
                                        <td>18 Oct 2021</td>
                                        <td>25 Nov 2021</td>
                                        <td><span class="badge bg-danger">Inactive</span></td>
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
                                        <td>James Wilson</td>
                                        <td>$50</td>
                                        <td>50</td>
                                        <td>$2500</td>
                                        <td>250</td>
                                        <td>01 Dec 2021</td>
                                        <td>01 Jan 2022</td>
                                        <td><span class="badge bg-success">Active</span></td>
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
                                        <td>Anne Marie</td>
                                        <td>$90</td>
                                        <td>18</td>
                                        <td>$1620</td>
                                        <td>130</td>
                                        <td>15 Jan 2022</td>
                                        <td>10 Feb 2022</td>
                                        <td><span class="badge bg-danger">Inactive</span></td>
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
                                        <td>Oliver Smith</td>
                                        <td>$220</td>
                                        <td>3</td>
                                        <td>$660</td>
                                        <td>40</td>
                                        <td>20 Feb 2022</td>
                                        <td>01 Mar 2022</td>
                                        <td><span class="badge bg-success">Active</span></td>
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
