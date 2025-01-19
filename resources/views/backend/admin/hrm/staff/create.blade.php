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
                            <form method="POST" action="{{ route('staff.store') }}" enctype="multipart/form-data">
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
                                                src="https://via.placeholder.com/70x60" 
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
                                        <input type="text" class="form-control" id="hr_code" name="hr_code" value="{{ old('hr_code') }}" placeholder="Enter Hr Code">
                                    </div>
        
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="full_name" class="form-label">Full Name
                                            @error('full_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name') }}" placeholder="Enter Fullname">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="sex" class="form-label">Sex</label>
                                        <select class="form-control" id="sex" name="sex">
                                            <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                                            <option value="Other" {{ old('sex') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>                                        
                                    </div>
                                </div>
        
                                <div class="row">

                                    <div class="col-md-4 mb-3">
                                        <label for="birthday" class="form-label">Birthday</label>
                                        <input type="date" class="form-control" id="birthday" name="birthday" value="{{ old('birthday') }}" placeholder="Enter Birthday">
                                    </div>
        
                                   
                                    <div class="col-md-4 mb-3">
                                        <label for="birthplace" class="form-label">Birthplace</label>
                                        <input type="text" class="form-control" id="birthplace" name="birthplace" value="{{ old('birthplace') }}" placeholder="Enter Birthplace">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="hometown" class="form-label">Hometown</label>
                                        <input type="text" class="form-control" id="hometown" name="hometown" value="{{ old('hometown') }}"  placeholder="Enter Hometown">
                                    </div>
                                </div>
        
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="marital_status" class="form-label">Marital Status</label>
                                        <select class="form-control" id="marital_status" name="marital_status">
                                            <option value="Single" {{ old('marital_status') == 'Single' ? 'selected' : '' }}>Single</option>
                                            <option value="Married" {{ old('marital_status') == 'Married' ? 'selected' : '' }}>Married</option>
                                            <option value="Divorced" {{ old('marital_status') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                            <option value="Widowed" {{ old('marital_status') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                        </select>
                                    </div>
        
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="nation" class="form-label">Nation</label>
                                        <input type="text" class="form-control" id="nation" name="nation" value="{{ old('nation') }}"  placeholder="Enter Bation">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="religion" class="form-label">Religion</label>
                                        <input type="text" class="form-control" id="religion" name="religion" value="{{ old('religion') }}"  placeholder="Enter Religion">
                                    </div>
                                </div>
        
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="id_document_type" class="form-label">ID Document Type</label>
                                        <input type="text" class="form-control" id="id_document_type" name="id_document_type"  value="{{ old('id_document_type') }}"  placeholder="Enter Id Documnent Type">
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="id_creation_date" class="form-label">ID Creation Date</label>
                                        <input type="date" class="form-control" id="id_creation_date" name="id_creation_date"  value="{{ old('id_creation_date') }}"  placeholder="Enter Id Creation Date">
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="place_of_issue" class="form-label">Place of Issue</label>
                                        <input type="text" class="form-control" id="place_of_issue" name="place_of_issue" value="{{ old('place_of_issue') }}" placeholder="Enter Place of Issue">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="resident_of" class="form-label">Resident Of</label>
                                        <input type="text" class="form-control" id="resident_of" name="resident_of" value="{{ old('resident_of') }}" placeholder="Enter Resident Of">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="current_address" class="form-label">Current Address</label>
                                        <input type="text" class="form-control" id="current_address" name="current_address" value="{{ old('current_address') }}" placeholder="Enter Current Address">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="literacy" class="form-label">Literacy</label>
                                        <input type="text" class="form-control" id="literacy" name="literacy" value="{{ old('literacy') }}" placeholder="Enter Literacy Level">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="Working" {{ old('status') == 'Working' ? 'selected' : '' }}>Working</option>
                                            <option value="Maternity" {{ old('status') == 'Maternity' ? 'selected' : '' }}>Maternity Leave</option>
                                            <option value="Inactivity" {{ old('status') == 'Inactivity' ? 'selected' : '' }}>Inactivity</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="job_position" class="form-label">Job Position</label>
                                        <select class="form-control" id="job_position" name="job_position">
                                            <option value="Operations" {{ old('job_position') == 'Operations' ? 'selected' : '' }}>Operations Manager</option>
                                            <option value="Engineer" {{ old('job_position') == 'Engineer' ? 'selected' : '' }}>Engineer</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-4 mb-3">
                                        <label for="workplace" class="form-label">Workplace</label>
                                        <select class="form-control" id="workplace" name="workplace">
                                            <option value="Texas" {{ old('workplace') == 'Texas' ? 'selected' : '' }}>Texas</option>
                                            <option value="Arizona" {{ old('workplace') == 'Arizona' ? 'selected' : '' }}>Arizona</option>
                                        </select>
                                    </div>                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="bank_account_number" class="form-label">Bank Account #</label>
                                        <input type="text" class="form-control" id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number') }}" placeholder="Enter Bank Account Number">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="account_name" class="form-label">Account Name</label>
                                        <input type="text" class="form-control" id="account_name" name="account_name" value="{{ old('account_name') }}" placeholder="Enter Account Name">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="bank_of_issue" class="form-label">Bank of Issue</label>
                                        <input type="text" class="form-control" id="bank_of_issue" name="bank_of_issue" value="{{ old('bank_of_issue') }}" placeholder="Enter Bank of Issue">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="personal_tax_code" class="form-label">Personal Tax Code</label>
                                        <input type="text" class="form-control" id="personal_tax_code" name="personal_tax_code" value="{{ old('personal_tax_code') }}" placeholder="Enter Personal Tax Code">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="hourly_rate" class="form-label">Hourly Rate</label>
                                        <input type="number" class="form-control" id="hourly_rate" name="hourly_rate" step="0.01" value="{{ old('hourly_rate', 0) }}" placeholder="Enter Hourly Rate">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Enter Phone Number">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="facebook" class="form-label">Facebook</label>
                                        <input type="text" class="form-control" id="facebook" name="facebook" value="{{ old('facebook') }}" placeholder="Enter Facebook Profile">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="linkedin" class="form-label">LinkedIn</label>
                                        <input type="text" class="form-control" id="linkedin" name="linkedin" value="{{ old('linkedin') }}" placeholder="Enter LinkedIn Profile">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="skype" class="form-label">Skype</label>
                                        <input type="text" class="form-control" id="skype" name="skype" value="{{ old('skype') }}" placeholder="Enter Skype ID">
                                    </div>
                                </div>
                                <div class="row">
                                   
                                    <div class="col-md-4 mb-3">
                                        <label for="previous_job" class="form-label">Previous Job</label>
                                        <input type="text" class="form-control" id="previous_job" name="previous_job" value="{{ old('previous_job') }}" placeholder="Enter Previous Job">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="publication_job" class="form-label">Publication Job</label>
                                        <input type="text" class="form-control" id="publication_job" name="publication_job" value="{{ old('publication_job') }}" placeholder="Enter Publication Job">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="email" class="form-label">Email
                                        <sup class="req text-danger">*</sup>
                                        @error('email')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        </label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Enter Email">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="default_language" class="form-label">Default Language</label>
                                        <select class="form-control select2" id="default_language" name="default_language">
                                            <option value="chinese" {{ old('default_language') == 'chinese' ? 'selected' : '' }}>Chinese</option>
                                            <option value="greek" {{ old('default_language') == 'greek' ? 'selected' : '' }}>Greek</option>
                                            <option value="dutch" {{ old('default_language') == 'dutch' ? 'selected' : '' }}>Dutch</option>
                                            <option value="romanian" {{ old('default_language') == 'romanian' ? 'selected' : '' }}>Romanian</option>
                                            <option value="czech" {{ old('default_language') == 'czech' ? 'selected' : '' }}>Czech</option>
                                            <option value="bulgarian" {{ old('default_language') == 'bulgarian' ? 'selected' : '' }}>Bulgarian</option>
                                            <option value="italian" {{ old('default_language') == 'italian' ? 'selected' : '' }}>Italian</option>
                                            <option value="persian" {{ old('default_language') == 'persian' ? 'selected' : '' }}>Persian</option>
                                            <option value="german" {{ old('default_language') == 'german' ? 'selected' : '' }}>German</option>
                                            <option value="catalan" {{ old('default_language') == 'catalan' ? 'selected' : '' }}>Catalan</option>
                                            <option value="russian" {{ old('default_language') == 'russian' ? 'selected' : '' }}>Russian</option>
                                            <option value="english" {{ old('default_language') == 'english' ? 'selected' : '' }}>English</option>
                                            <option value="slovak" {{ old('default_language') == 'slovak' ? 'selected' : '' }}>Slovak</option>
                                            <option value="polish" {{ old('default_language') == 'polish' ? 'selected' : '' }}>Polish</option>
                                            <option value="portuguese" {{ old('default_language') == 'portuguese' ? 'selected' : '' }}>Portuguese</option>
                                            <option value="ukrainian" {{ old('default_language') == 'ukrainian' ? 'selected' : '' }}>Ukrainian</option>
                                            <option value="vietnamese" {{ old('default_language') == 'vietnamese' ? 'selected' : '' }}>Vietnamese</option>
                                            <option value="swedish" {{ old('default_language') == 'swedish' ? 'selected' : '' }}>Swedish</option>
                                            <option value="spanish" {{ old('default_language') == 'spanish' ? 'selected' : '' }}>Spanish</option>
                                            <option value="french" {{ old('default_language') == 'french' ? 'selected' : '' }}>French</option>
                                            <option value="japanese" {{ old('default_language') == 'japanese' ? 'selected' : '' }}>Japanese</option>
                                            <option value="turkish" {{ old('default_language') == 'turkish' ? 'selected' : '' }}>Turkish</option>
                                            <option value="indonesia" {{ old('default_language') == 'indonesia' ? 'selected' : '' }}>Indonesia</option>
                                            <option value="portuguese_br" {{ old('default_language') == 'portuguese_br' ? 'selected' : '' }}>Portuguese_br</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="direction" class="form-label">Direction</label>
                                        <select class="form-control" id="direction" name="direction">
                                            <option value="LTR" {{ old('direction') == 'LTR' ? 'selected' : '' }}>LTR</option>
                                            <option value="RTL" {{ old('direction') == 'RTL' ? 'selected' : '' }}>RTL</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-5 mb-3">
                                        <label for="certificates" class="form-label">Certificates</label>
                                        <input type="file" class="form-control" id="certificates" name="certificates[]" multiple accept="image/*">
                                    </div>
                                    <div class="col-md-1 mb-3 mt-3">
                                        <div id="certificatePreviewContainer" style="display: flex; gap: 10px;">

                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="documents" class="form-label">Documents</label>
                                        <input type="file" class="form-control" id="documents" name="documents">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="email_signature" class="form-label">Email Signature</label>
                                        <textarea class="form-control" id="email_signature" name="email_signature" placeholder="Enter Email Signature">{{ old('email_signature') }}</textarea>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="other_information" class="form-label">Other Information</label>
                                        <textarea class="form-control" id="other_information" name="other_information" placeholder="Enter Other Information">{{ old('other_information') }}</textarea>
                                    </div>                          
                                </div>
                                <div class="row">
                                    <!-- Password Field -->
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="control-label">
                                            <small class="req text-danger">*</small> Password
                                        </label>
                                        <div class="input-group">
                                            <input 
                                                type="password" 
                                                class="form-control password" 
                                                id="password" 
                                                name="password" 
                                                placeholder="Enter Password"
                                                autocomplete="off">
                                            
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
                                            <small class="req text-danger">*</small> Confirm Password
                                        </label>
                                        <div class="input-group">
                                            <input 
                                                type="password" 
                                                class="form-control password" 
                                                id="confirm_password" 
                                                name="confirm_password"
                                                placeholder="Enter Confirm Password" 
                                                autocomplete="off">
                                            
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
                                    <div class="col-md-2 mb-3">
                                        <label for="is_administrator" class="form-label"></label>
                                        <!-- checkbox -->
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                              <input 
                                                    type="checkbox" 
                                                    id="checkboxPrimary1" 
                                                    name="is_administrator" 
                                                    value="1" >
                                                <label for="checkboxPrimary1">Administrator</label>
                                            </div>
                                          </div>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="is_administrator" class="form-label"></label>
                                        <!-- checkbox -->
                                        <div class="form-group clearfix">
                                            <div class="icheck-primary d-inline">
                                                <input 
                                                type="checkbox" 
                                                id="checkboxPrimary2" 
                                                name="send_welcome_email" 
                                                value="1" >
                                            <label for="checkboxPrimary2">Send Welcome Email</label>
                                            </div>
                                          </div>
                                    </div>  
                                </div>

                                <!-- Educational Qualification Section -->
                                <div class="row" id="education-section">
                                    <h5 class="col-12 font-weight-bolder">Educational Qualification</h5>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="institution_name">Institution Name</label>
                                            <input type="text" class="form-control" name="institution_name[]" placeholder="Enter institution name">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="subject_name">Subject Name</label>
                                            <input type="text" class="form-control" name="subject_name[]" placeholder="Enter subject name">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="starting_year">Starting Year</label>
                                            <input type="date" class="form-control" name="starting_year[]">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="passing_year">Passing Year</label>
                                            <input type="date" class="form-control" name="passing_year[]">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" name="description[]" rows="3" placeholder="Enter description"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-12">
                                        <button type="button" class="btn btn-success w-100" id="add-qualification">
                                            <i class="fas fa-plus-circle"></i> Add More
                                        </button>
                                    </div>
                                </div>

                                <!-- Certification Section -->
                                <div class="row" id="certification-section">
                                    <h5 class="col-12 font-weight-bolder">Certifications</h5>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="certification">Certification Reservation Place Name</label>
                                            <input type="text" class="form-control" name="certification[]" placeholder="Enter Certification reservation place name">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="certification_name">Certification Name</label>
                                            <input type="text" class="form-control" name="certification_name[]" placeholder="Enter certification name">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="year">Year</label>
                                            <input type="date" class="form-control" name="year[]">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" name="description[]" rows="3" placeholder="Enter description"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-12">
                                        <button type="button" class="btn btn-success w-100" id="add-certification">
                                            <i class="fas fa-plus-circle"></i> Add More
                                        </button>
                                    </div>
                                </div>

                                <!-- Award Section -->
                                <div class="row" id="award-section">
                                    <h5 class="col-12 font-weight-bolder">Awards</h5>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="award_name">Award Name</label>
                                            <input type="text" class="form-control" name="award_name[]" placeholder="Enter award name">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="award_year">Year</label>
                                            <input type="date" class="form-control" name="award_year[]">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="award_description">Description</label>
                                            <textarea class="form-control" name="award_description[]" rows="3" placeholder="Enter description"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-12">
                                        <button type="button" class="btn btn-success w-100" id="add-award">
                                            <i class="fas fa-plus-circle"></i> Add More
                                        </button>
                                    </div>
                                </div>

                                <!-- Employment History Section -->
                                <div class="row" id="employment-section">
                                    <h5 class="col-12 font-weight-bolder">Employment History</h5>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="company_name">Company Name</label>
                                            <input type="text" class="form-control" name="company_name[]" placeholder="Enter company name">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="position">Position</label>
                                            <input type="text" class="form-control" name="position[]" placeholder="Enter position">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="starting_year">Starting Year</label>
                                            <input type="date" class="form-control" name="starting_year[]">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="ending_year">Ending Year</label>
                                            <input type="date" class="form-control" name="ending_year[]">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" name="description[]" rows="3" placeholder="Enter description"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group col-12">
                                        <button type="button" class="btn btn-success w-100" id="add-employment">
                                            <i class="fas fa-plus-circle"></i> Add More
                                        </button>
                                    </div>
                                </div>           
                
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;"><i class="fas fa-plus"></i> Add Staff</button>
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

    document.getElementById('certificates').addEventListener('change', function (event) {
    const previewContainer = document.getElementById('certificatePreviewContainer');
    previewContainer.innerHTML = ''; // Clear existing previews

    Array.from(event.target.files).forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = `Certificate ${index + 1}`;
                img.style.cssText = `
                    width: 70px;
                    height: 60px;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                    object-fit: cover;
                `;

                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    });
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

    $(document).ready(function () {
        // Educational Qualification Section
        $('#add-qualification').click(function () {
            var newQualification = $('#education-section').first().clone();
            newQualification.find("input, textarea").val('');
            newQualification.find('#add-qualification').html('<i class="fas fa-minus-circle"></i> Remove');
            newQualification.find('#add-qualification').removeClass('btn-success').addClass('btn-danger').attr('id', 'remove-qualification');
            $('#education-section').last().after(newQualification);
        });

        // Certification Section
        $('#add-certification').click(function () {
            var newCertification = $('#certification-section').first().clone();
            newCertification.find("input, textarea").val('');
            newCertification.find('#add-certification').html('<i class="fas fa-minus-circle"></i> Remove');
            newCertification.find('#add-certification').removeClass('btn-success').addClass('btn-danger').attr('id', 'remove-certification');
            $('#certification-section').last().after(newCertification);
        });

        // Award Section
        $('#add-award').click(function () {
            var newAward = $('#award-section').first().clone();
            newAward.find("input, textarea").val('');
            newAward.find('#add-award').html('<i class="fas fa-minus-circle"></i> Remove');
            newAward.find('#add-award').removeClass('btn-success').addClass('btn-danger').attr('id', 'remove-award');
            $('#award-section').last().after(newAward);
        });

        // Employment History Section
        $('#add-employment').click(function () {
            var newEmployment = $('#employment-section').first().clone();
            newEmployment.find("input, textarea").val('');
            newEmployment.find('#add-employment').html('<i class="fas fa-minus-circle"></i> Remove');
            newEmployment.find('#add-employment').removeClass('btn-success').addClass('btn-danger').attr('id', 'remove-employment');
            $('#employment-section').last().after(newEmployment);
        });

        // Remove Section
        $(document).on('click', '.btn-danger', function () {
            $(this).closest('.row').remove();
        });
    });


</script>
@endpush