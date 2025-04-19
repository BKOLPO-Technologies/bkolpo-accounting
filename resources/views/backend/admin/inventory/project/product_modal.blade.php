<div class="modal fade" id="createProductModal" tabindex="-1" role="dialog" aria-labelledby="createProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="createProductModalLabel">
                    Add New Product
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color:white;">&times;</span>
                </button>
            </div>
            <form id="createProductForm">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">

                            <!-- Product Name -->
                            <div class="form-group">
                                <label for="new_product_name">Product Name</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="new_product_name" name="name" placeholder="Enter Product Name" required>
                                </div>
                            </div>

                            <!-- Product Price -->
                            <div class="form-group">
                                <label for="new_product_price">Product Price</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="new_product_price" placeholder="Enter Product Price" name="price">
                                </div>
                            </div>

                            <!-- Unit Name -->
                            {{-- <div class="form-group">
                                <label for="new_unit_name">Unit Name</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="new_unit_name" placeholder="Enter Unit Name" name="unit_id">
                                </div>
                            </div> --}}

                            <!-- Unit Name -->
                            <div class="form-group">
                                <label for="unit_id">Unit</label>
                                <select name="unit_id" id="unit_id" class="form-control select2" required>
                                    <option value="">Select Unit</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}">
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            
                        </div>
                        
                        <div class="col-md-6">
                            
                            <!-- Category Name -->
                            {{-- <div class="form-group">
                                <label for="new_category_name">Category Name</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="new_category_name" placeholder="Enter Category Name" name="category_id">
                                </div>
                            </div> --}}

                            <!-- Category Name -->
                            <div class="form-group">
                                <label for="category_id">Category</label>
                                <select name="category_id" id="category_id" class="form-control select2" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Product Code -->
                            <div class="form-group">
                                <label for="new_product_code">Product Code</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="new_product_code" name="code">
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="form-group">
                                <label for="new_description">Description</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="new_description" name="description" placeholder="Enter Short Description">
                                </div>
                            </div>
                            
                        </div>
                    </div>  
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Client
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>