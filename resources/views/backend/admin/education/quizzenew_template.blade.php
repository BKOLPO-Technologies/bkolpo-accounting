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
                    <form> 
                        <div class="card-body">
                            <div class="mb-3"> 
                                <label class="form-label">Email</label> 
                                <div class="input-group">
                                    <input type="email" class="form-control" name="email" placeholder="Enter your email">
                                </div>
                            </div>

                            <div class="mb-3"> 
                                <label class="form-label">Name</label> 
                                <div class="input-group">
                                    <input type="text" class="form-control" name="name" placeholder="Enter your name">
                                </div>
                            </div>

                            <div class="mb-3"> 
                                <label class="form-label">Password</label> 
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" placeholder="Enter your password"> 
                                </div>
                            </div>

                            <div class="mb-3"> 
                                <label class="form-label">Retype Password</label> 
                                <div class="input-group">
                                    <input type="password" class="form-control" name="re_password" placeholder="Retype your password"> 
                                </div>
                            </div>

                            <div class="mb-3"> 
                                <label class="form-label">Phone</label> 
                                <div class="input-group">
                                    <input type="tel" class="form-control" name="phone" placeholder="Enter your phone number"> 
                                </div>
                            </div>

                            <div class="mb-3"> 
                                <label class="form-label">Language</label> 
                                <div class="input-group">
                                    <select class="form-select" required>
                                        <option selected disabled value="">Choose...</option>
                                        <option>...</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a valid state.
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3"> 
                                <label class="form-label">Time Zone</label> 
                                <div class="input-group">
                                    <select class="form-select" required>
                                        <option selected disabled value="">Choose...</option>
                                        <option>...</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a valid state.
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3"> 
                                <label class="form-label">Currency</label> 
                                <div class="input-group">
                                    <select class="form-select" required>
                                        <option selected disabled value="">Choose...</option>
                                        <option>...</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a valid state.
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 d-flex justify-content-between align-items-center"> 
                                <div>
                                    Join email newsletter
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="newsletterToggle" name="newsletterToggle">
                                    <label class="form-check-label" for="newsletterToggle"></label>
                                </div>
                            </div>

                            <div class="mb-3 d-flex justify-content-between align-items-center"> 
                                <div>
                                    Enable profile messages
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="profileMessagesToggle" name="profileMessagesToggle">
                                    <label class="form-check-label" for="profileMessagesToggle"></label>
                                </div>
                            </div>

                        </div> 

                        <div class="card-footer"> 
                            <button type="submit" class="btn btn-primary">Submit</button> 
                        </div> 

                    </form> 
                </div> 
                
            </div> 
        </div>
    </div>

</main>
@endsection
