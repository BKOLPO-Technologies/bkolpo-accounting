@extends('layouts.admin')
@section('admin')
<style>
    /* Ensure the content is full height and centered at the top */
    .content {
        display: flex;
        justify-content: center;
        align-items: flex-start; /* Align items at the top */
        height: 100vh; /* 100% of the viewport height */
        background-color: #f4f6f9; /* Default background color */
        overflow: hidden;
    }

    .welcome-message {
        text-align: center;
        font-size: 2rem;
        color: #fff;
        opacity: 0;
        padding: 20px;
        border-radius: 10px;
        position: absolute;
        top: 10%; /* Position the message near the top */
        animation: fadeIn 3s forwards, changeBackgroundColor 3s infinite alternate;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Animation to change background color */
    @keyframes changeBackgroundColor {
        0% {
            background-color: #007bff; /* Initial background color */
        }
        50% {
            background-color: #28a745; /* Midway background color */
        }
        100% {
            background-color: #ff5722; /* Final background color */
        }
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $pageTitle ?? 'N/A' }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">{{ $pageTitle ?? 'N/A' }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="welcome-message">
            <h2>Welcome to the Admin Dashboard</h2>
        </div>
    </div>
    <!-- /.content -->
</div>

@endsection
