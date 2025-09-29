@extends('layouts.admin')
@section('admin')

<div class="content-wrapper">
    
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                <h1>Product Edit</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: black;">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.product.index') }}" style="text-decoration: none; color: black;">Product</a></li>
                        <li class="breadcrumb-item active">Edit</li>
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
                    <h4 class="mb-0">Edit Product</h4>
                    <a href="{{ route('admin.product.index')}}" class="btn btn-sm btn-danger rounded-0">
                        <i class="fa-solid fa-arrow-left"></i> Back To List
                    </a>
                </div>
              </div>
              
              <form action="{{ route('admin.product.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

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
                                    <input type="text" class="form-control" value="{{ old('name', $product->name) }}" name="name">
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
                                        <option value="{{ $category->id }}" 
                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
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
                                    <input type="number" class="form-control" value="{{ old('price', $product->price) }}" name="price" step="0.01">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Product Code
                                    @error('product_code')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-cogs"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="{{ old('product_code', $product->product_code) }}" name="product_code">
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
                                        <option value="{{ $unit->id }}" 
                                            {{ old('unit_id', $product->unit_id) == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                                
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
                                    <option value="sales" {{ old('group_name', $product->group_name) == 'sales' ? 'selected' : '' }}>Sales</option>
                                    <option value="purchases" {{ old('group_name', $product->group_name) == 'purchases' ? 'selected' : '' }}>Purchases</option>
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
                                <label>Description</label>
                                <textarea name="description" class="form-control" placeholder="Enter Description">{{ old('description', $product->description) }}</textarea>
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
                                
                                @if($product->image)
                                    <img id="imagePreview" src="{{ asset('upload/inventory/products/' . $product->image) }}" alt="Image Preview" style="display:block; margin-top: 10px; height:80px; width: 80px;">
                                @else
                                    <img id="imagePreview" src="{{ asset('backend/logo.jpg') }}" alt="Image Preview" style="display:block; margin-top: 10px; height:80px; width: 80px;">
                                @endif
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
                                    <option value="1" {{ old('status', $product->status) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status', $product->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                </div>

                <div class="card-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">Update</button>
                </div>

              </form>
            </div>
          </div>
        </div>
      </div>

    </section>


</div>
@endsection


@push('js')
<script>
    // Preview Image on file input change
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
@endpush