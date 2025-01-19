@extends('layouts.admin', [$pageTitle => 'Activity Create'])

@section('admin')
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
                        <li class="breadcrumb-item active"> HR Management / {{ $pageTitle ?? 'N/A' }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline shadow-lg">
                        <div class="card-header py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                                <a href="{{ route('attendance.index') }}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('activity.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="staff_id">Select Staff</label>
                                            <select name="staff_id" id="staff_id" class="form-control select2" required>
                                                <option value="">Select Staff</option>
                                                @foreach($staffs as $staff)
                                                    <option value="{{ $staff->id }}">{{ $staff->full_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('staff_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="date_time">Date and Time</label>
                                            <input type="datetime-local" name="date_time" id="date_time" class="form-control"
                                                value="{{ old('date_time', now('Asia/Dhaka')->format('Y-m-d\TH:i')) }}" required>
                                            @error('date_time')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="latitude">Latitude</label>
                                            <input type="text" name="latitude" id="latitude" class="form-control" placeholder="Enter Latitude" readonly>
                                            @error('latitude')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="longitude">Longitude</label>
                                            <input type="text" name="longitude" id="longitude" class="form-control" placeholder="Enter Longitude" readonly>
                                            @error('longitude')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="location">Location</label>
                                            <div class="input-group">
                                                <input type="text" name="location" id="location" class="form-control" placeholder="Enter Location" readonly>
                                                <button type="button" class="btn btn-primary" onclick="getLiveLocation()">Get Location</button>
                                            </div>
                                            @error('location')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-success float-right">
                                            <i class="fas fa-plus"></i> Add Activity
                                        </button>
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
    // Initialize Select2
    $(document).ready(function () {
        $('.select2').select2();
    });

    // Fetch the live location
    function getLiveLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(success, error);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function success(position) {
        const lat = position.coords.latitude;
        const long = position.coords.longitude;

        // Display latitude and longitude in the form fields
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = long;

        // Fetch location address using OpenStreetMap Nominatim API
        fetchLocationAddress(lat, long);
    }

    function error() {
        alert("Unable to retrieve your location. Please enable location permissions.");
    }

    function fetchLocationAddress(lat, long) {
        const apiUrl = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${long}&zoom=18&addressdetails=1`;

        fetch(apiUrl)
            .then(response => response.json())
            .then(data => {
                const location = data.display_name || "Address not found";
                document.getElementById('location').value = location;
            })
            .catch(() => {
                alert("Unable to fetch the address. Please try again.");
            });
    }
</script>
@endpush
