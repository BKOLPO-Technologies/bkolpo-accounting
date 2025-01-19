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
            <div class="col-md-12"> 
                <div class="card card-primary card-outline mb-4"> 
                    <div class="card-header">
                        <div class="card-title">{{ $pageTitle ?? 'N/A' }}</div>
                    </div> 
                    <div class="card-body">
                        <!-- Tabs Navigation -->
                        <ul class="nav nav-pills" id="myTab3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link text-light active" id="register_date-tab" data-bs-toggle="tab" href="#register_date" role="tab" aria-controls="register_date" aria-selected="true">Registration Date</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" id="course_count-tab" data-bs-toggle="tab" href="#course_count" role="tab" aria-controls="course_count" aria-selected="false">Courses Count</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" id="course_rate-tab" data-bs-toggle="tab" href="#course_rate" role="tab" aria-controls="course_rate" aria-selected="false">Courses Rating</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" id="sale_count-tab" data-bs-toggle="tab" href="#sale_count" role="tab" aria-controls="sale_count" aria-selected="false">Sales</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" id="support_rate-tab" data-bs-toggle="tab" href="#support_rate" role="tab" aria-controls="support_rate" aria-selected="false">Support Rating</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" id="product_sale_count-tab" data-bs-toggle="tab" href="#product_sale_count" role="tab" aria-controls="product_sale_count" aria-selected="false">Store Sales Count</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" id="make_topic-tab" data-bs-toggle="tab" href="#make_topic" role="tab" aria-controls="make_topic" aria-selected="false">Forum Topics</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" id="send_post_in_topic-tab" data-bs-toggle="tab" href="#send_post_in_topic" role="tab" aria-controls="send_post_in_topic" aria-selected="false">Forum Replies</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" id="instructor_blog-tab" data-bs-toggle="tab" href="#instructor_blog" role="tab" aria-controls="instructor_blog" aria-selected="false">Articles</a>
                            </li>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="register_date" role="tabpanel" aria-labelledby="register_date-tab">
                                <div class="card-body">
                                    <form>
                                        <div class="mb-3">
                                            <label for="regDate" class="form-label">Registration Date</label>
                                            <input type="date" class="form-control" id="regDate">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit Registration Date</button>
                                    </form>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="course_count" role="tabpanel" aria-labelledby="course_count-tab">
                                <div class="card-body">
                                    <form>
                                        <div class="mb-3">
                                            <label for="courseCount" class="form-label">Courses Count</label>
                                            <input type="number" class="form-control" id="courseCount" placeholder="Enter number of courses">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit Courses Count</button>
                                    </form>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="course_rate" role="tabpanel" aria-labelledby="course_rate-tab">
                                <div class="card-body">
                                    <form>
                                        <div class="mb-3">
                                            <label for="courseRate" class="form-label">Courses Rating</label>
                                            <input type="number" class="form-control" id="courseRate" placeholder="Enter course rating" step="0.1" min="0" max="5">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit Courses Rating</button>
                                    </form>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="sale_count" role="tabpanel" aria-labelledby="sale_count-tab">
                                <div class="card-body">
                                    <form>
                                        <div class="mb-3">
                                            <label for="saleCount" class="form-label">Sales Count</label>
                                            <input type="number" class="form-control" id="saleCount" placeholder="Enter sales count">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit Sales Count</button>
                                    </form>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="support_rate" role="tabpanel" aria-labelledby="support_rate-tab">
                                <div class="card-body">
                                    <form>
                                        <div class="mb-3">
                                            <label for="supportRate" class="form-label">Support Rating</label>
                                            <input type="number" class="form-control" id="supportRate" placeholder="Enter support rating" step="0.1" min="0" max="5">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit Support Rating</button>
                                    </form>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="product_sale_count" role="tabpanel" aria-labelledby="product_sale_count-tab">
                                <div class="card-body">
                                    <form>
                                        <div class="mb-3">
                                            <label for="productSaleCount" class="form-label">Store Sales Count</label>
                                            <input type="number" class="form-control" id="productSaleCount" placeholder="Enter product sales count">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit Store Sales Count</button>
                                    </form>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="make_topic" role="tabpanel" aria-labelledby="make_topic-tab">
                                <div class="card-body">
                                    <form>
                                        <div class="mb-3">
                                            <label for="forumTopic" class="form-label">Forum Topic</label>
                                            <input type="text" class="form-control" id="forumTopic" placeholder="Enter forum topic">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit Forum Topic</button>
                                    </form>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="send_post_in_topic" role="tabpanel" aria-labelledby="send_post_in_topic-tab">
                                <div class="card-body">
                                    <form>
                                        <div class="mb-3">
                                            <label for="forumReply" class="form-label">Forum Reply</label>
                                            <textarea class="form-control" id="forumReply" rows="3" placeholder="Enter your reply"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit Forum Reply</button>
                                    </form>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="instructor_blog" role="tabpanel" aria-labelledby="instructor_blog-tab">
                                <div class="card-body">
                                    <form>
                                        <div class="mb-3">
                                            <label for="articleTitle" class="form-label">Article Title</label>
                                            <input type="text" class="form-control" id="articleTitle" placeholder="Enter article title">
                                        </div>
                                        <div class="mb-3">
                                            <label for="articleContent" class="form-label">Article Content</label>
                                            <textarea class="form-control" id="articleContent" rows="3" placeholder="Enter article content"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit Article</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div> 
        </div>
    </div>

</main>

<style>
.nav-pills .nav-link.active {
    background-color: #007bff; /* Adjust as needed */
    color: white !important; /* Active tab text color */
}

.nav-pills .nav-link {
    color: #000; /* Default text color for inactive tabs */
}
</style>
@endsection
