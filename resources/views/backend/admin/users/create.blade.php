@extends('layouts.admin', ['pageTitle' => 'User Create'])

@section('admin')
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #ff1190 !important
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #ffffff !important;
    }
</style>
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-6">
                  <h1 class="m-0">{{ $pageTitle ?? 'N/A'}}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">{{ $pageTitle ?? 'N/A'}}</li>
                  </ol>
                </div><!-- /.col -->
              </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-outline shadow-lg">
                            <div class="card-header py-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                                    <a href="{{ route('users.index')}}" class="btn btn-sm btn-danger rounded-0">
                                        <i class="fa-solid fa-arrow-left"></i> Back To List
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                            <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Name
                @error('name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-user"></i></span> <!-- User icon -->
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter Name">
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Email
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-envelope"></i></span> <!-- Email icon -->
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Enter Email">
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Password Field -->
        <div class="col-md-6 mb-3">
            <label for="password" class="control-label">
                <small class="req text-danger"></small> Password
            </label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-lock"></i></span> <!-- Lock icon -->
                <input 
                    type="password" 
                    class="form-control password" 
                    id="password" 
                    name="password" 
                    placeholder="Enter Password"
                    autocomplete="off" required>
                
                <!-- Password Visibility Toggle -->
                <span class="input-group-text">
                    <a style="cursor: pointer;"  class="show_password" onclick="showPassword('password'); return true;">
                        <i class="fa fa-eye"></i>
                    </a>
                </span>
                
                <!-- Generate Password -->
                <span class="input-group-text">
                    <a style="cursor: pointer;" class="generate_password" onclick="generatePassword('password'); return false;">
                        <i class="fa fa-refresh"></i>
                    </a>
                </span>
            </div>
        </div>
    
        <!-- Confirm Password Field -->
        <div class="col-md-6 mb-3">
            <label for="confirm_password" class="control-label">
                <small class="req text-danger"></small> Confirm Password  
                <small id="passwordMatchMessage" class="text-danger font-weight-bolder"></small> <!-- Message element -->
            </label>
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-lock"></i></span> <!-- Lock icon -->
                <input 
                    type="password" 
                    class="form-control password" 
                    id="confirm_password" 
                    name="confirm_password"
                    placeholder="Enter Confirm Password" 
                    autocomplete="off"
                    onkeyup="checkPasswordMatch();" required> <!-- Add onkeyup event -->
                
                <!-- Password Visibility Toggle -->
                <span class="input-group-text">
                    <a href="#confirm_password" class="show_password" onclick="showPassword('confirm_password'); return false;">
                        <i class="fa fa-eye"></i>
                    </a>
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Role:</strong>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa fa-user-shield"></i></span> <!-- Shield icon -->
                    <select name="roles[]" class="form-control select2" multiple="multiple">
                        @foreach ($roles as $value => $label)
                            <option value="{{ $value }}">
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;"><i class="fas fa-plus"></i> Add User</button>
        </div>
    </div> 
</form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('js')
<script>
    // Toggle Password Visibility
    function showPassword(fieldId) {
        const field = document.getElementById(fieldId);
        if (field.type === "password") {
            field.type = "text"; // Show Password
        } else {
            field.type = "password"; // Hide Password
        }
    }

    // Generate Random Password
    function generatePassword(fieldId) {
        const chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+";
        const passwordLength = 12;
        let password = "";
        for (let i = 0; i < passwordLength; i++) {
            const randomIndex = Math.floor(Math.random() * chars.length);
            password += chars[randomIndex];
        }

        // Target the input field using the provided fieldId
        const inputField = document.getElementById(fieldId);
        if (inputField) {
            inputField.value = password; // Set the generated password

            // Show a Toastr notification
            toastr.success('Password generated successfully: ' + password);
        }
    }

    function checkPasswordMatch() {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm_password').value;
        const messageElement = document.getElementById('passwordMatchMessage');

        if (password && confirmPassword) {
            if (password === confirmPassword) {
                messageElement.textContent = 'Passwords match.';
                messageElement.classList.remove('text-danger');
                messageElement.classList.add('text-success'); // Optional: Change text color to green
            } else {
                messageElement.textContent = 'Passwords do not match.';
                messageElement.classList.remove('text-success');
                messageElement.classList.add('text-danger'); // Optional: Change text color to red
            }
        } else {
            messageElement.textContent = ''; // Clear message if fields are empty
        }
    }

    // select 2
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select roles",
            allowClear: true
        });
    });

</script>
@endpush
