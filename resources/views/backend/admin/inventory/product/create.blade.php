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
                                    <input type="text" class="form-control" placeholder="Enter Product Name" name="name">
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
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                                    <input type="number" class="form-control" placeholder="Enter Product Price" name="price">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Quantity
                                    @error('quantity')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-cogs"></i></span>
                                    </div>
                                    <input type="number" class="form-control" placeholder="Enter Product Quantity" name="quantity">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Description
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                                <textarea name="description" placeholder="Enter Product Description" class="form-control"></textarea>
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
@endpush