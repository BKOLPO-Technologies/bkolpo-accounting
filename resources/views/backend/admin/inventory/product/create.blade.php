@extends('layouts.admin')
@section('admin')

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Product Create</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: black;">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.product.index') }}" style="text-decoration: none; color: black;">Product</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        
      <div class="container-fluid">
        <div class="row">
          
          <div class="col-md-12">
            
            <div class="card card-primary card-outline shadow-lg">
              <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Add New Product</h4>
                    <a href="{{ route('admin.product.index')}}" class="btn btn-sm btn-danger rounded-0">
                        <i class="fa-solid fa-arrow-left"></i> Back To List
                    </a>
                </div>
              </div>
            
              <form role="form" action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Name
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-box"></i></span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Enter Product Name" name="name" value="{{ old('name') }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label for="category_id" class="form-label">Category Name</label>

                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-network-wired"></i></span>

                                <select name="category_id" id="category_id" class="form-control select2">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                
                                <div class="input-group-append">
                                    <button class="btn btn-danger" type="button" id="addCategoryBtn" data-toggle="modal" data-target="#createCategoryModal">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>

                            </div>

                            @error('category_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Price
                                    @error('price')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    </div>
                                    <input type="number" class="form-control" placeholder="Enter Product Price" name="price" step="0.01" value="{{ old('price') }}">

                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Product Code
                                    @error('code')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-cogs"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="code" value="{{ old('code', $productCode) }}">

                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="unit_id" class="form-label">Unit Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-network-wired"></i></span>
                    
                                <select name="unit_id" id="unit_id" class="form-control select2">
                                    <option value="">Select unit</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                    
                                <div class="input-group-append">
                                    <button class="btn btn-danger" type="button" id="addUnitBtn" data-toggle="modal" data-target="#createUnitModal">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                    
                            </div>
                    
                            @error('unit_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    
                        <div class="col-md-6">
                            <label for="group_name" class="form-label">Group Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-users"></i></span>
                    
                                <select name="group_name" id="group_name" class="form-control select2">
                                    <option value="">Select Group</option>
                                    <option value="sales" {{ old('group_name') == 'sales' ? 'selected' : '' }}>Sales</option>
                                    <option value="purchases" {{ old('group_name') == 'purchases' ? 'selected' : '' }}>Purchases</option>
                                </select>
                    
                                <div class="input-group-append">
                                  
                                </div>
                            </div>
                    
                            @error('group_name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>                    

                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Description
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <textarea name="description" placeholder="Enter Product Description" class="form-control">{{ old('description') }}</textarea>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Image</label>
                                <input type="file" class="form-control" name="image" id="imageInput">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group">
                                <img id="imagePreview" src="" alt="Image Preview" style="display:none; margin-top: 10px; height:80px; height:70px;">
                            </div>
                        </div>

                        <div class="col-md-6 mb-2">
                            <label for="status" class="form-label">Status
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-check-circle"></i></span>

                                <select class="form-control" id="status" name="status">
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                                                
                            </div>
                        </div>

                    </div>

                    <div class="row mt-2">
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary bg-success text-light" style="float: right;">
                                <i class="fas fa-plus"></i> Add Product
                            </button>
                        </div>
                    </div>

                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

    </section>

</div>

<!-- Modal for creating a new branch -->
@include('backend.admin.inventory.unit.unit_modal')
@include('backend.admin.inventory.category.category_modal')
@endsection

@push('js')
<script>
    document.getElementById('imageInput').addEventListener('change', function(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('imagePreview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    });
</script>

<script> 
    $('#createUnitForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        let formData = $(this).serialize(); // Get form data

        $.ajax({
            url: '{{ route('admin.unit.store2') }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                // Check if the supplier was created successfully
                if (response.success) {
                    // Close the modal
                    $('#createUnitModal').modal('hide');
                    
                    // Clear form inputs
                    $('#createUnitForm')[0].reset();

                    // Append new supplier to the supplier select dropdown
                    $('#unit_id').append(new Option(response.unit.name, response.unit.id));

                    // Re-initialize the select2 to refresh the dropdown
                    $('#unit_id').trigger('change');

                    // Show success message
                    toastr.success('Unit added successfully!');
                } else {
                    toastr.error('Something went wrong. Please try again.');
                }
            },
            error: function(response) {
                // Handle error (validation errors, etc.)
                let errors = response.responseJSON.errors;
                for (let field in errors) {
                    $(`#new_unit_${field}`).addClass('is-invalid');
                    $(`#new_unit_${field}`).after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
                }
            }
        });
    });
</script>


<script> 
    $('#createCategoryForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        let formData = $(this).serialize(); // Get form data

        $.ajax({
            url: '{{ route('admin.category.store2') }}',
            type: 'POST',
            data: formData,
            success: function(response) {
                // Check if the supplier was created successfully
                if (response.success) {
                    // Close the modal
                    $('#createCategoryModal').modal('hide');
                    
                    // Clear form inputs
                    $('#createCategoryForm')[0].reset();

                    // Append new supplier to the supplier select dropdown
                    $('#category_id').append(new Option(response.category.name, response.category.id));

                    // Re-initialize the select2 to refresh the dropdown
                    $('#category_id').trigger('change');

                    // Show success message
                    toastr.success('Category added successfully!');
                } else {
                    toastr.error('Something went wrong. Please try again.');
                }
            },
            error: function(response) {
                // Handle error (validation errors, etc.)
                let errors = response.responseJSON.errors;
                for (let field in errors) {
                    $(`#new_category_${field}`).addClass('is-invalid');
                    $(`#new_category_${field}`).after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
                }
            }
        });
    });
</script>

@endpush