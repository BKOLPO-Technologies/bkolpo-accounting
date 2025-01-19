@extends('layouts.admin', [$pageTitle => 'Leave Application Show'])

@section('admin')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{ $pageTitle ?? 'N/A' }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active"> HR Management / {{ $pageTitle ?? 'N/A' }}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary card-outline shadow-lg">
                        <div class="card-header py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">{{ $pageTitle ?? 'N/A' }}</h4>
                                <a href="{{ route('staff.list')}}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List Staff
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <tr>
                                        <th>HR Code</th>
                                        <td>{{ $staff->hr_code ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Full Name</th>
                                        <td>{{ $staff->full_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $staff->email ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Birthday</th>
                                        <td>{{ \Carbon\Carbon::parse($staff->birthday)->format('j F Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Sex</th>
                                        <td>{{ $staff->sex ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Hometown</th>
                                        <td>{{ $staff->hometown ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Last Login</th>
                                        <td>{{ \Carbon\Carbon::parse($staff->last_login)->format('j F Y h:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Marital Status</th>
                                        <td>{{ $staff->marital_status ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nationality</th>
                                        <td>{{ $staff->nation ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Religion</th>
                                        <td>{{ $staff->religion ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>ID Document Type</th>
                                        <td>{{ $staff->id_document_type ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>ID Creation Date</th>
                                        <td>{{ \Carbon\Carbon::parse($staff->id_creation_date)->format('j F Y') ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Place of Issue</th>
                                        <td>{{ $staff->place_of_issue ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Resident of</th>
                                        <td>{{ $staff->resident_of ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Current Address</th>
                                        <td>{{ $staff->current_address ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Literacy</th>
                                        <td>{{ $staff->literacy ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>{{ $staff->status ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Job Position</th>
                                        <td>{{ $staff->job_position ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Workplace</th>
                                        <td>{{ $staff->workplace ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Bank Account Number</th>
                                        <td>{{ $staff->bank_account_number ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Account Name</th>
                                        <td>{{ $staff->account_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Bank of Issue</th>
                                        <td>{{ $staff->bank_of_issue ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Personal Tax Code</th>
                                        <td>{{ $staff->personal_tax_code ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Hourly Rate</th>
                                        <td>{{ $staff->hourly_rate ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td>{{ $staff->phone ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Facebook</th>
                                        <td>{{ $staff->facebook ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>LinkedIn</th>
                                        <td>{{ $staff->linkedin ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Skype</th>
                                        <td>{{ $staff->skype ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Default Language</th>
                                        <td>{{ $staff->default_language ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Direction</th>
                                        <td>{{ $staff->direction ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email Signature</th>
                                        <td>{{ $staff->email_signature ?? 'N/A' }}</td>
                                    </tr>
                                   <!-- Education -->
                                    @foreach ($staff->educations as $education)
                                    <tr>
                                        <th>Educational Qualifications</th>
                                        <td>
                                            Institution Name: {{ $education->institution_name ?? 'N/A' }}<br>
                                            Subject Name: {{ $education->subject_name ?? 'N/A' }}<br>
                                            Starting Year: {{ \Carbon\Carbon::parse($education->starting_year)->format('d F Y') ?? 'N/A' }}<br>
                                            Passing Year: {{ \Carbon\Carbon::parse($education->passing_year)->format('d F Y') ?? 'N/A' }}<br>
                                            Description: {{ $education->description ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    @endforeach

                                    <!-- Certification -->
                                    @foreach ($staff->certifications as $certification)
                                    <tr>
                                        <th>Certifications</th>
                                        <td>
                                            Certification Name: {{ $certification->certification_name ?? 'N/A' }}<br>
                                            Year: {{ \Carbon\Carbon::parse($certification->year)->format('d F Y') ?? 'N/A' }}<br>
                                            Description: {{ $certification->description ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    @endforeach

                                    <!-- Awards -->
                                    @foreach ($staff->awards as $award)
                                    <tr>
                                        <th>Awards</th>
                                        <td>
                                            Award Name: {{ $award->award_name ?? 'N/A' }}<br>
                                            Year: {{ \Carbon\Carbon::parse($award->year)->format('d F Y') ?? 'N/A' }}<br>
                                            Description: {{ $award->description ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    @endforeach

                                    <!-- Employment History -->
                                    @foreach ($staff->employmentHistories as $employment)
                                    <tr>
                                        <th>Employment History</th>
                                        <td>
                                            Company Name: {{ $employment->company_name ?? 'N/A' }}<br>
                                            Position: {{ $employment->position ?? 'N/A' }}<br>
                                            Starting Year: {{ \Carbon\Carbon::parse($employment->starting_year)->format('d F Y') ?? 'N/A' }}<br>
                                            Ending Year: {{ \Carbon\Carbon::parse($employment->ending_year)->format('d F Y') ?? 'N/A' }}<br>
                                            Description: {{ $employment->description ?? 'N/A' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                    
                                    <tr>
                                        <th>Previous Job</th>
                                        <td>{{ $staff->previous_job ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Publication Job</th>
                                        <td>{{ $staff->publication_job ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Active</th>
                                        <td>
                                            @if($staff->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Administrator</th>
                                        <td>
                                            @if($staff->is_administrator)
                                                <span class="badge bg-info">Yes</span>
                                            @else
                                                <span class="badge bg-secondary">No</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Profile Image</th>
                                        <td>
                                            @if($staff->profile_image)
                                                <img src="{{ asset($staff->profile_image) }}" alt="Profile Image" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
                                            @else
                                                <img src="https://via.placeholder.com/70x60" alt="No Profile Image" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
                                            @endif
                                        </td>
                                    </tr>                                    
                                    <tr>
                                        <th>Certificates</th>
                                        <td>
                                            @if($staff->certificates && $staff->certificates->isNotEmpty())
                                                @foreach($staff->certificates as $certificate)
                                                    <img src="{{ asset('storage/' . $certificate->certificates) }}" alt="Certificate Image" class="img-fluid mb-2" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                                @endforeach
                                            @else
                                                <span>No Certificates Available</span>
                                            @endif
                                        </td>
                                    </tr>                                    
                                    <tr>
                                        <th>Documents</th>
                                        <td>
                                            @if($staff->documents && !empty($staff->documents))
                                                @foreach(explode(',', $staff->documents) as $document)
                                                    <a href="{{ asset('storage/' . $document) }}" target="_blank" class="btn btn-success mb-2">View Document</a>
                                                @endforeach
                                            @else
                                                <span>No Documents Available</span>
                                            @endif
                                        </td>
                                    </tr>
                                                                                     
                                </tbody>
                            </table>
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
    // Initialize Select2 if necessary
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endpush
