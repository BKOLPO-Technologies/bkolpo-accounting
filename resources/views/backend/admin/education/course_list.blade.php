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
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box"> <span class="info-box-icon text-bg-primary shadow-sm"> <i class="bi bi-gear-fill"></i> </span>
                            <div class="info-box-content"> <span class="info-box-text">Total Courses</span> <span class="info-box-number">
                                    10
                                    <small></small> </span> </div> <!-- /.info-box-content -->
                        </div> <!-- /.info-box -->
                    </div> <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box"> <span class="info-box-icon text-bg-danger shadow-sm"> <i class="bi bi-hand-thumbs-up-fill"></i> </span>
                            <div class="info-box-content"> <span class="info-box-text">Pending Review Courses</span> <span class="info-box-number">0</span> </div> <!-- /.info-box-content -->
                        </div> <!-- /.info-box -->
                    </div> <!-- /.col --> <!-- fix for small devices only --> <!-- <div class="clearfix hidden-md-up"></div> -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box"> <span class="info-box-icon text-bg-success shadow-sm"> <i class="bi bi-cart-fill"></i> </span>
                            <div class="info-box-content"> <span class="info-box-text">Total Duration</span> <span class="info-box-number">14:25 Hours</span> </div> <!-- /.info-box-content -->
                        </div> <!-- /.info-box -->
                    </div> <!-- /.col -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box"> <span class="info-box-icon text-bg-warning shadow-sm"> <i class="bi bi-people-fill"></i> </span>
                            <div class="info-box-content"> <span class="info-box-text">Total Sales</span> <span class="info-box-number">20</span> </div> <!-- /.info-box-content -->
                        </div> <!-- /.info-box -->
                    </div> <!-- /.col -->
                </div>
            </div>
        </div>
        <div class="app-content">
            <div class="container-fluid">
                <div class="card card-primary card-outline mb-4">
                    <div class="card-body">
                        <div class="row">
                            <!-- Column 1 -->
                            <div class="col-md-3 mb-3">
                                <label>Search</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" name="from" class="form-control" placeholder="Enter Search">
                                </div>
                            </div>

                            <!-- Column 2 -->
                            <div class="col-md-3 mb-3">
                                <label>Start Date</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    <input type="date" name="to" class="form-control">
                                </div>
                            </div>

                             <!-- Column 3 -->
                             <div class="col-md-3 mb-3">
                                <label>End Date</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                    <input type="date" name="to" class="form-control">
                                </div>
                            </div>

                            <!-- Column 4 -->
                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <label class="input-label">Filters</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-filter"></i></span> <!-- Icon -->
                                        <select name="sort" data-plugin-selecttwo="" class="form-select populate">
                                            <option value="">Filter Type</option>
                                            <option value="has_discount">Discounted Courses</option>
                                            <option value="sales_asc">Sales - Ascending</option>
                                            <option value="sales_desc">Sales - Descending</option>
                                            <option value="price_asc">Price - Ascending</option>
                                            <option value="price_desc">Price - Descending</option>
                                            <option value="income_asc">Income - Ascending</option>
                                            <option value="income_desc">Income - Descending</option>
                                            <option value="created_at_asc">Created Date - Ascending</option>
                                            <option value="created_at_desc">Created Date - Descending</option>
                                            <option value="updated_at_asc">Updated Date - Ascending</option>
                                            <option value="updated_at_desc">Updated Date - Descending</option>
                                            <option value="public_courses">Public Courses</option>
                                            <option value="courses_private">Private Courses</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Column 5 -->
                            <div class="col-md-3 mb-3">
                                <label>Instructor</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-users"></i></span>
                                    <input type="text" name="from" class="form-control" placeholder="Enter Teachers">
                                </div>
                            </div>

                            <!-- Column 6 -->
                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <label class="input-label">Category</label> <!-- Moved label outside the input group -->
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-filter"></i></span> <!-- Icon -->
                                        <select name="category_id" data-plugin-selecttwo="" class="form-select populate">
                                            <option value="">All Categories</option>
                                            <option value="520">Design</option>
                                            <optgroup label="Academics">
                                                <option value="601">Math</option>
                                                <option value="602">Science</option>
                                                <option value="603">Language</option>
                                            </optgroup>
                                            <option value="523">Health &amp; Fitness</option>
                                            <optgroup label="Lifestyle">
                                                <option value="604">Lifestyle</option>
                                                <option value="605">Beauty &amp; Makeup</option>
                                            </optgroup>
                                            <option value="525">Marketing</option>
                                            <optgroup label="Business">
                                                <option value="609">Management</option>
                                                <option value="610">Communications</option>
                                                <option value="611">Business Strategy</option>
                                            </optgroup>
                                            <optgroup label="Development">
                                                <option value="606">Web Development</option>
                                                <option value="607">Mobile Development</option>
                                                <option value="608">Game Development</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            

                            <!-- Column 7 -->
                            <div class="col-md-3 mb-3">
                                <div class="form-group">
                                    <label class="input-label">Status</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-flag"></i></span> <!-- Icon for Status -->
                                        <select name="status" data-plugin-selecttwo="" class="form-select populate">
                                            <option value="">All Statuses</option>
                                            <option value="pending">Pending Review</option>
                                            <option value="active">Published</option>
                                            <option value="inactive">Rejected</option>
                                            <option value="is_draft">Draft</option>
                                        </select>
                                    </div>
                                </div>
                                
                            </div>

                            <!-- Column 8 -->
                            <div class="col-md-3 d-flex align-items-end  mb-3">
                                <input type="button" value="Show Results" class="btn btn-success form-control btn-block">
                            </div>
                        </div>
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
