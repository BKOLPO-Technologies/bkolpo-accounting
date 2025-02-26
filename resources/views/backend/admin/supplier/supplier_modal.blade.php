<div class="modal fade" id="createSupplierModal" tabindex="-1" role="dialog" aria-labelledby="createSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="createSupplierModalLabel">
                    <i class="fas fa-user-plus"></i> Add New Supplier
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color:white;">&times;</span>
                </button>
            </div>
            <form id="createSupplierForm">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Name -->
                            <div class="form-group">
                                <label for="new_supplier_name">Name</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_supplier_name" name="name"  placeholder="Enter Supplier Name" required>
                                </div>
                            </div>
                            <!-- Company -->
                            <div class="form-group">
                                <label for="new_supplier_company">Company</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_supplier_company" placeholder="Enter Company Name" name="company">
                                </div>
                            </div>
                            <!-- Phone -->
                            <div class="form-group">
                                <label for="new_supplier_phone">Phone</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_supplier_phone" placeholder="Enter Phone No" name="phone">
                                </div>
                            </div>
                            <!-- Email -->
                            <div class="form-group">
                                <label for="new_supplier_email">Email</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email" class="form-control" id="new_supplier_email" name="email" placeholder="Enter Client Email">
                                </div>
                            </div>
                            <!-- Address -->
                            <div class="form-group">
                                <label for="new_supplier_address">Address</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_supplier_address" name="address" placeholder="Enter Address">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <!-- City -->
                            <div class="form-group">
                                <label for="new_supplier_city">City</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-city"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_supplier_city" name="city" placeholder="Enter Address">
                                </div>
                            </div>
                            <!-- Region -->
                            <div class="form-group">
                                <label for="new_supplier_region">Region</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_supplier_region" name="region" placeholder="Enter Region">
                                </div>
                            </div>
                            <!-- Country -->
                            <div class="form-group">
                                <label for="new_supplier_country">Country</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-flag"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_supplier_country" name="country" placeholder="Enter Country">
                                </div>
                            </div>
                            <!-- Post Box -->
                            <div class="form-group">
                                <label for="new_supplier_postbox">Post Box</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-inbox"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_supplier_postbox" name="postbox" placeholder="Enter Post Box">
                                </div>
                            </div>
                            <!-- TAX ID -->
                            <div class="form-group">
                                <label for="new_supplier_taxid">TAX ID</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-receipt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_supplier_taxid" name="taxid"  placeholder="Enter Transaction ID">
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
                        <i class="fas fa-save"></i> Save Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>