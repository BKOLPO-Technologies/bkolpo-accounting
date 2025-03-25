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

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $pageTitle ?? 'N/A' }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">{{ $pageTitle ?? 'N/A' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        {{-- <div class="welcome-message">
            <h2>Welcome to the Admin Dashboard</h2>
        </div> --}}
        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                        <h3>{{ $projectTotalAmount }}</h3>
        
                        <p>Project Total Order</p>
                        </div>
                        <div class="icon">
                        <i class="ion ion-bag"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            More info <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                        <h3>{{ $projectTotalAmountPaid }}</h3>
        
                        <p>Project Total Amount Paid</p>
                        </div>
                        <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                        <h3>{{ $projectTotalAmountDue }}</h3>
        
                        <p>Project Total Amount Due</p>
                        </div>
                        <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $purchaseTotalAmount }}</h3>
                            <p>Purchase Total</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                    
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 col-6">
                    <!-- PIE CHART -->
                    <div class="card card-danger">
                        <div class="card-header">
                        <h3 class="card-title">Pie Chart</h3>
        
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                            </button>
                            {{-- <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button> --}}
                        </div>
                        </div>
                        <div class="card-body">
                        <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                    <!-- ----- -->
                </div>

                <div class="col-lg-6 col-6">
                    <!-- BAR CHART -->
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Bar Chart</h3>
        
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                                </button>
                                {{-- <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button> --}}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

@endsection

@push('js')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // PIE CHART SETUP /////////////////////////////////////////////////////////////////////////////////
        var pieChartCanvas = document.getElementById('pieChart');

        if (!pieChartCanvas) {
            console.error("Pie chart canvas element not found!");
            return;
        }

        var pieChartContext = pieChartCanvas.getContext('2d');

        // Dynamic data passed from Laravel Blade
        var projectTotalAmount = {{ $projectTotalAmount }};
        var projectTotalAmountPaid = {{ $projectTotalAmountPaid }};
        var projectTotalAmountDue = {{ $projectTotalAmountDue }};
        var purchaseTotalAmount = {{ $purchaseTotalAmount }};

        var pieData = {
            labels: ['Total Project Amount', 'Total Paid', 'Total Due', 'Total Purchase'],
            datasets: [{
                data: [projectTotalAmount, projectTotalAmountPaid, projectTotalAmountDue, purchaseTotalAmount],
                backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef'],
            }]
        };


        var pieOptions = {
            responsive: true,
            maintainAspectRatio: false
        };

        new Chart(pieChartContext, {
            type: 'pie',
            data: pieData,
            options: pieOptions
        });

        // BAR CHART SETUP /////////////////////////////////////////////////////////////////////////////////////
        var barChartCanvas = document.getElementById('barChart');

        if (!barChartCanvas) {
            console.error("Bar chart canvas element not found!");
            return;
        }

        var barChartContext = barChartCanvas.getContext('2d');

        var barData = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June'],
            datasets: [{
                label: 'Revenue',
                data: [650, 590, 800, 810, 560, 550],
                backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
                borderColor: '#fff',
                borderWidth: 1
            }]
        };

        var barOptions = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        };

        new Chart(barChartContext, {
            type: 'bar',
            data: barData,
            options: barOptions
        });
    });
</script>
@endpush
