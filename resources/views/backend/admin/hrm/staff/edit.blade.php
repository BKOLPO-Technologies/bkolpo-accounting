@extends('layouts.admin', [$pageTitle => 'Create Staff'])
@section('admin')
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
                            <form method="POST" action="{{ route('staff.update',$staff->id) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-11 mb-3">
                                        <label for="profile_image" class="form-label">Profile Image</label>
                                        <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                                    </div>
                                    <div class="col-md-1 mb-3">
                                        <!-- Preview Container -->
                                        <div class="mt-3">
                                            <img
                                                id="profileImagePreview"
                                                src="{{ (!empty($staff->profile_image)) ? url('upload/staff/'.$staff->profile_image):url('https://via.placeholder.com/70x60') }}" 
                                                alt="Profile Preview"
                                                style="width: 70px; height: 60px; border: 1px solid #ddd; border-radius: 5px; object-fit: cover;">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="hr_code" class="form-label">HR Code
                                            @error('hr_code')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </label>
                                        <input type="text" class="form-control" id="hr_code" name="hr_code" value="{{ $staff->hr_code }}" placeholder="Enter Hr Code">
                                    </div>
        
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="full_name" class="form-label">Full Name
                                            @error('full_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" value="{{ $staff->full_name }}" placeholder="Enter Fullname">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="sex" class="form-label">Sex</label>
                                        <select class="form-control" id="sex" name="sex">
                                            <option value="Male" {{ $staff->sex == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ $staff->sex == 'Female' ? 'selected' : '' }}>Female</option>
                                            <option value="Other" {{ $staff->sex == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>                                        
                                    </div>
                                </div>
        
                                <div class="row">

                                    <div class="col-md-4 mb-3">
                                        <label for="birthday" class="form-label">Birthday</label>
                                        <input type="date" class="form-control" id="birthday" name="birthday" value="{{ $staff->birthday }}" placeholder="Enter Birthday">
                                    </div>
        
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="birthplace" class="form-label">Birthplace</label>
                                        <input type="text" class="form-control" id="birthplace" name="birthplace" value="{{ $staff->birthplace }}" placeholder="Enter Birthplace">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="hometown" class="form-label">Hometown</label>
                                        <input type="text" class="form-control" id="hometown" name="hometown" value="{{ $staff->hometown }}"  placeholder="Enter Hometown">
                                    </div>
                                </div>
        
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="marital_status" class="form-label">Marital Status</label>
                                        <select class="form-control" id="marital_status" name="marital_status">
                                            <option value="Single" {{ $staff->marital_status == 'Single' ? 'selected' : '' }}>Single</option>
                                            <option value="Married" {{ $staff->marital_status == 'Married' ? 'selected' : '' }}>Married</option>
                                            <option value="Divorced" {{ $staff->marital_status == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                            <option value="Widowed" {{ $staff->marital_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                        </select>
                                    </div>
        
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="nation" class="form-label">Nation</label>
                                        <input type="text" class="form-control" id="nation" name="nation" value="{{ $staff->nation }}"  placeholder="Enter Bation">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="religion" class="form-label">Religion</label>
                                        <input type="text" class="form-control" id="religion" name="religion" value="{{ $staff->religion }}"  placeholder="Enter Religion">
                                    </div>
                                </div>
        
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="id_document_type" class="form-label">ID Document Type</label>
                                        <input type="text" class="form-control" id="id_document_type" name="id_document_type"  value="{{ $staff->id_document_type }}"  placeholder="Enter Id Documnent Type">
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="id_creation_date" class="form-label">ID Creation Date</label>
                                        <input type="date" class="form-control" id="id_creation_date" name="id_creation_date"  value="{{ $staff->id_creation_date }}"  placeholder="Enter Id Creation Date">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="place_of_issue" class="form-label">Place of Issue</label>
                                        <input type="text" class="form-control" id="place_of_issue" name="place_of_issue" value="{{ $staff->place_of_issue }}" placeholder="Enter Place of Issue">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="resident_of" class="form-label">Resident Of</label>
                                        <input type="text" class="form-control" id="resident_of" name="resident_of" value="{{ $staff->resident_of }}" placeholder="Enter Resident Of">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="current_address" class="form-label">Current Address</label>
                                        <input type="text" class="form-control" id="current_address" name="current_address" value="{{ $staff->current_address }}" placeholder="Enter Current Address">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="literacy" class="form-label">Literacy</label>
                                        <input type="text" class="form-control" id="literacy" name="literacy" value="{{ $staff->literacy }}" placeholder="Enter Literacy Level">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-control" id="status" name="status">
                                            <optstatus value="Working" {{ $staff->status == 'Working' ? 'selected' : '' }}>Working</option>
                                            <option value="Maternity" {{ $staff->status == 'Maternity' ? 'selected' : '' }}>Maternity Leave</option>
                                            <option value="Inactivity" {{ $staff->status == 'Inactivity' ? 'selected' : '' }}>Inactivity</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="job_position" class="form-label">Job Position</label>
                                        <select class="form-control" id="job_position" name="job_position">
                                            <option value="Operations" {{ $staff->job_position  == 'Operations' ? 'selected' : '' }}>Operations Manager</option>
                                            <option value="Engineer" {{ $staff->job_position  == 'Engineer' ? 'selected' : '' }}>Engineer</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="workplace" class="form-label">Workplace</label>
                                        <select class="form-control" id="workplace" name="workplace">
                                            <option value="Texas" {{ $staff->workplace == 'Texas' ? 'selected' : '' }}>Texas</option>
                                            <option value="Arizona" {{ $staff->workplace == 'Arizona' ? 'selected' : '' }}>Arizona</option>
                                        </select>
                                    </div>                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="bank_account_number" class="form-label">Bank Account #</label>
                                        <input type="text" class="form-control" id="bank_account_number" name="bank_account_number" value="{{ $staff->bank_account_number }}" placeholder="Enter Bank Account Number">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="account_name" class="form-label">Account Name</label>
                                        <input type="text" class="form-control" id="account_name" name="account_name" value="{{ $staff->account_name }}" placeholder="Enter Account Name">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="bank_of_issue" class="form-label">Bank of Issue</label>
                                        <input type="text" class="form-control" id="bank_of_issue" name="bank_of_issue" value="{{ $staff->bank_of_issue }}" placeholder="Enter Bank of Issue">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="personal_tax_code" class="form-label">Personal Tax Code</label>
                                        <input type="text" class="form-control" id="personal_tax_code" name="personal_tax_code" value="{{ $staff->personal_tax_code }}" placeholder="Enter Personal Tax Code">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="hourly_rate" class="form-label">Hourly Rate</label>
                                        <input type="number" class="form-control" id="hourly_rate" name="hourly_rate" step="0.01" value="{{ $staff->hourly_rate }}" placeholder="Enter Hourly Rate">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" class="form-control" id="phone" name="phone" value="{{ $staff->phone }}" placeholder="Enter Phone Number">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="facebook" class="form-label">Facebook</label>
                                        <input type="text" class="form-control" id="facebook" name="facebook" value="{{ $staff->facebook }}" placeholder="Enter Facebook Profile">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="linkedin" class="form-label">LinkedIn</label>
                                        <input type="text" class="form-control" id="linkedin" name="linkedin" value="{{ $staff->linkedin }}" placeholder="Enter LinkedIn Profile">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="skype" class="form-label">Skype</label>
                                        <input type="text" class="form-control" id="skype" name="skype" value="{{ $staff->skype }}" placeholder="Enter Skype ID">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="previous_job" class="form-label">Previous Job</label>
                                        <input type="text" class="form-control" id="previous_job" name="previous_job" value="{{ $staff->previous_job }}" placeholder="Enter Previous Job">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="publication_job" class="form-label">Publication Job</label>
                                        <input type="text" class="form-control" id="publication_job" name="publication_job" value="{{ $staff->publication_job }}" placeholder="Enter Publication Job">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="email" class="form-label">Email
                                        <sup class="req text-danger">*</sup>
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        </label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{  $staff->email }}" placeholder="Enter Email">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="default_language" class="form-label">Default Language</label>
                                        <select class="form-control select2" id="default_language" name="default_language">
                                            <option value="chinese" {{ $staff->default_language == 'chinese' ? 'selected' : '' }}>Chinese</option>
                                            <option value="greek" {{ $staff->default_language == 'greek' ? 'selected' : '' }}>Greek</option>
                                            <option value="dutch" {{ $staff->default_language == 'dutch' ? 'selected' : '' }}>Dutch</option>
                                            <option value="romanian" {{ $staff->default_language == 'romanian' ? 'selected' : '' }}>Romanian</option>
                                            <option value="czech" {{ $staff->default_language == 'czech' ? 'selected' : '' }}>Czech</option>
                                            <option value="bulgarian" {{ $staff->default_language == 'bulgarian' ? 'selected' : '' }}>Bulgarian</option>
                                            <option value="italian" {{ $staff->default_language == 'italian' ? 'selected' : '' }}>Italian</option>
                                            <option value="persian" {{ $staff->default_language == 'persian' ? 'selected' : '' }}>Persian</option>
                                            <option value="german" {{ $staff->default_language == 'german' ? 'selected' : '' }}>German</option>
                                            <option value="catalan" {{ $staff->default_language == 'catalan' ? 'selected' : '' }}>Catalan</option>
                                            <option value="russian" {{ $staff->default_language == 'russian' ? 'selected' : '' }}>Russian</option>
                                            <option value="english" {{ $staff->default_language == 'english' ? 'selected' : '' }}>English</option>
                                            <option value="slovak" {{ $staff->default_language == 'slovak' ? 'selected' : '' }}>Slovak</option>
                                            <option value="polish" {{ $staff->default_language == 'polish' ? 'selected' : '' }}>Polish</option>
                                            <option value="portuguese" {{ $staff->default_language == 'portuguese' ? 'selected' : '' }}>Portuguese</option>
                                            <option value="ukrainian" {{ $staff->default_language == 'ukrainian' ? 'selected' : '' }}>Ukrainian</option>
                                            <option value="vietnamese" {{ $staff->default_language == 'vietnamese' ? 'selected' : '' }}>Vietnamese</option>
                                            <option value="swedish" {{ $staff->default_language == 'swedish' ? 'selected' : '' }}>Swedish</option>
                                            <option value="spanish" {{ $staff->default_language == 'spanish' ? 'selected' : '' }}>Spanish</option>
                                            <option value="french" {{ $staff->default_language == 'french' ? 'selected' : '' }}>French</option>
                                            <option value="japanese" {{ $staff->default_language == 'japanese' ? 'selected' : '' }}>Japanese</option>
                                            <option value="turkish" {{ $staff->default_language == 'turkish' ? 'selected' : '' }}>Turkish</option>
                                            <option value="indonesia" {{ $staff->default_language == 'indonesia' ? 'selected' : '' }}>Indonesia</option>
                                            <option value="portuguese_br" {{ $staff->default_language == 'portuguese_br' ? 'selected' : '' }}>Portuguese_br</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="direction" class="form-label">Direction</label>
                                        <select class="form-control" id="direction" name="direction">
                                            <option value="LTR" {{ $staff->direction == 'LTR' ? 'selected' : '' }}>LTR</option>
                                            <option value="RTL" {{ $staff->direction == 'RTL' ? 'selected' : '' }}>RTL</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="email_signature" class="form-label">Email Signature</label>
                                        <textarea class="form-control" id="email_signature" name="email_signature" placeholder="Enter Email Signature">{{ $staff->email_signature }}</textarea>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="other_information" class="form-label">Other Information</label>
                                        <textarea class="form-control" id="other_information" name="other_information" placeholder="Enter Other Information">{{ $staff->other_information }}</textarea>
                                    </div>                          
                                </div>
                                <div class="row">
                                    <!-- Administrator Checkbox -->
                                    <div class="col-md-2 mb-3">
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input 
                                                    type="checkbox" 
                                                    id="checkboxPrimary1" 
                                                    name="is_administrator" 
                                                    value="1" 
                                                    {{ $staff->is_administrator ? 'checked' : '' }}>
                                                <label for="checkboxPrimary1">Administrator</label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Send Welcome Email Checkbox -->
                                    <div class="col-md-2 mb-3">
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input 
                                                    type="checkbox" 
                                                    id="checkboxPrimary2" 
                                                    name="send_welcome_email" 
                                                    value="1" 
                                                    {{ $staff->send_welcome_email ? 'checked' : '' }}>
                                                <label for="checkboxPrimary2">Send Welcome Email</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Educational Qualification Section -->
                                <div class="row" id="education-section">
                                    <h5 class="col-12 font-weight-bolder">Educational Qualification</h5>
                                    @if($staff->educations->isNotEmpty())
                                        @foreach($staff->educations as $education)
                                            <input type="hidden" name="education_ids[]" value="{{ $education->id ?? '' }}">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="institution_name">Institution Name</label>
                                                    <input type="text" class="form-control" name="institution_name[]" value="{{ $education->institution_name }}" placeholder="Enter institution name">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="subject_name">Subject Name</label>
                                                    <input type="text" class="form-control" name="subject_name[]" value="{{ $education->subject_name }}" placeholder="Enter subject name">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="starting_year">Starting Year</label>
                                                    <input type="date" class="form-control" name="starting_year[]" value="{{ $education->starting_year }}">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="passing_year">Passing Year</label>
                                                    <input type="date" class="form-control" name="passing_year[]" value="{{ $education->passing_year }}">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <textarea class="form-control" name="description[]" rows="3">{{ $education->description }}</textarea>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    <div class="form-group col-12">
                                        <button type="button" class="btn btn-success w-100" id="add-qualification">
                                            <i class="fas fa-plus-circle"></i> Add More
                                        </button>
                                    </div>
                                </div>

                                <!-- Certification Section -->
                                <div class="row" id="certification-section">
                                    <h5 class="col-12 font-weight-bolder">Certifications</h5>
                                    @if($staff->certifications->isNotEmpty())
                                        @foreach($staff->certifications as $certification)
                                            <input type="hidden" name="certification_ids[]" value="{{ $certification->id ?? '' }}">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="certification">Certification Reservation Place Name</label>
                                                    <input type="text" class="form-control" name="certification[]" value="{{ $certification->certification }}" placeholder="Enter Certification reservation place name">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="certification_name">Certification Name</label>
                                                    <input type="text" class="form-control" name="certification_name[]" value="{{ $certification->certification_name }}" placeholder="Enter certification name">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="year">Year</label>
                                                    <input type="date" class="form-control" name="year[]" value="{{ $certification->year }}">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <textarea class="form-control" name="certification_description[]" rows="3">{{ $certification->description }}</textarea>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    <div class="form-group col-12">
                                        <button type="button" class="btn btn-success w-100" id="add-certification">
                                            <i class="fas fa-plus-circle"></i> Add More
                                        </button>
                                    </div>
                                </div>

                                <!-- Award Section -->
                                <div class="row" id="award-section">
                                    <h5 class="col-12 font-weight-bolder">Awards</h5>
                                    @if($staff->awards->isNotEmpty())
                                        @foreach($staff->awards as $award)
                                            <input type="hidden" name="award_ids[]" value="{{ $award->id ?? '' }}">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="award_name">Award Name</label>
                                                    <input type="text" class="form-control" name="award_name[]" value="{{ $award->award_name }}" placeholder="Enter award name">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="award_year">Year</label>
                                                    <input type="date" class="form-control" name="award_year[]" value="{{ $award->year }}">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="award_description">Description</label>
                                                    <textarea class="form-control" name="award_description[]" rows="3">{{ $award->description }}</textarea>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    <div class="form-group col-12">
                                        <button type="button" class="btn btn-success w-100" id="add-award">
                                            <i class="fas fa-plus-circle"></i> Add More
                                        </button>
                                    </div>
                                </div>

                                <!-- Employment History Section -->
                                <div class="row" id="employment-section">
                                    <h5 class="col-12 font-weight-bolder">Employment History</h5>
                                    @if($staff->employmentHistories->isNotEmpty())
                                        @foreach($staff->employmentHistories as $employment)
                                            <input type="hidden" name="employment_ids[]" value="{{ $employment->id ?? '' }}">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="company_name">Company Name</label>
                                                    <input type="text" class="form-control" name="company_name[]" value="{{ $employment->company_name }}" placeholder="Enter company name">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="position">Position</label>
                                                    <input type="text" class="form-control" name="position[]" value="{{ $employment->position }}" placeholder="Enter position">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="starting_year">Starting Year</label>
                                                    <input type="date" class="form-control" name="starting_year[]" value="{{ $employment->starting_year }}">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="ending_year">Ending Year</label>
                                                    <input type="date" class="form-control" name="ending_year[]" value="{{ $employment->ending_year }}">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <textarea class="form-control" name="employment_description[]" rows="3">{{ $employment->description }}</textarea>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                    <div class="form-group col-12">
                                        <button type="button" class="btn btn-success w-100" id="add-employment">
                                            <i class="fas fa-plus-circle"></i> Add More
                                        </button>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;"><i class="far fa-paper-plane"></i> Update Staff</button>
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
    // Staff Profile Photo Show
    document.getElementById('profile_image').addEventListener('change', function (event) {
        const file = event.target.files[0]; // Get the selected file
        const preview = document.getElementById('profileImagePreview'); // Get the preview element
        const defaultImage = 'https://via.placeholder.com/70x60'; // Online default image URL

        if (file) {
            const reader = new FileReader(); // Create a FileReader object

            reader.onload = function (e) {
                preview.src = e.target.result; // Set the image source to the uploaded file
            };

            reader.readAsDataURL(file); // Read the file as a data URL
        } else {
            preview.src = defaultImage; // Reset to the default online image
        }
    });

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
        }
    }


    // Select 2 
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2();
    });


    // Function to handle remove button for each section using event delegation
    function addRemoveEventListener(sectionSelector, rowClass, removeButtonClass) {
        document.querySelector(sectionSelector).addEventListener('click', (event) => {
            // Check if the clicked element is the remove button (using event delegation)
            if (event.target && event.target.classList.contains(removeButtonClass.slice(1))) {
                const row = event.target.closest(rowClass); // Find the closest row to the clicked button
                if (row) {
                    row.remove(); // Remove the row
                }
            }
        });
    }

    $(document).ready(function () {
        // Module 1: Education
        function initializeEducationModule() {
            $('#add-qualification').on('click', function () {
                const educationSection = `
                <div class="row education-item">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="institution_name">Institution Name</label>
                            <input type="text" class="form-control" name="institution_name[]" placeholder="Enter institution name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="subject_name">Subject Name</label>
                            <input type="text" class="form-control" name="subject_name[]" placeholder="Enter subject name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="starting_year">Starting Year</label>
                            <input type="date" class="form-control" name="starting_year[]">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="passing_year">Passing Year</label>
                            <input type="date" class="form-control" name="passing_year[]">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" name="description[]" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-danger remove-qualification"><i class="fas fa-minus-circle"></i> Remove</button>
                    </div>
                </div>`;
                $('#education-section').append(educationSection);
            });

            $('#education-section').on('click', '.remove-qualification', function () {
                $(this).closest('.education-item').remove();
            });
        }

        // Module 2: Certification
        function initializeCertificationModule() {
            $('#add-certification').on('click', function () {
                const certificationSection = `
                <div class="row certification-item">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="certification">Certification Reservation Place Name</label>
                            <input type="text" class="form-control" name="certification[]" placeholder="Enter certification reservation place name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="certification_name">Certification Name</label>
                            <input type="text" class="form-control" name="certification_name[]" placeholder="Enter certification name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="year">Year</label>
                            <input type="date" class="form-control" name="year[]">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" name="description[]" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-danger remove-certification"><i class="fas fa-minus-circle"></i> Remove</button>
                    </div>
                </div>`;
                $('#certification-section').append(certificationSection);
            });

            $('#certification-section').on('click', '.remove-certification', function () {
                $(this).closest('.certification-item').remove();
            });
        }

        // Module 3: Awards
        function initializeAwardModule() {
            $('#add-award').on('click', function () {
                const awardSection = `
                <div class="row award-item">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="award_name">Award Name</label>
                            <input type="text" class="form-control" name="award_name[]" placeholder="Enter award name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="award_year">Year</label>
                            <input type="date" class="form-control" name="award_year[]">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="award_description">Description</label>
                            <textarea class="form-control" name="award_description[]" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-danger remove-award"><i class="fas fa-minus-circle"></i> Remove</button>
                    </div>
                </div>`;
                $('#award-section').append(awardSection);
            });

            $('#award-section').on('click', '.remove-award', function () {
                $(this).closest('.award-item').remove();
            });
        }

        // Module 4: Employment
        function initializeEmploymentModule() {
            $('#add-employment').on('click', function () {
                const employmentSection = `
                <div class="row employment-item">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="company_name">Company Name</label>
                            <input type="text" class="form-control" name="company_name[]" placeholder="Enter company name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="position">Position</label>
                            <input type="text" class="form-control" name="position[]" placeholder="Enter position">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="starting_year">Starting Year</label>
                            <input type="date" class="form-control" name="starting_year[]">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ending_year">Ending Year</label>
                            <input type="date" class="form-control" name="ending_year[]">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" name="description[]" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-danger remove-employment"><i class="fas fa-minus-circle"></i> Remove</button>
                    </div>
                </div>`;
                $('#employment-section').append(employmentSection);
            });

            $('#employment-section').on('click', '.remove-employment', function () {
                $(this).closest('.employment-item').remove();
            });
        }

        // Initialize all modules
        initializeEducationModule();
        initializeCertificationModule();
        initializeAwardModule();
        initializeEmploymentModule();
    });
</script>
@endpush