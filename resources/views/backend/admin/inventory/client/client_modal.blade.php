<div class="modal fade" id="createClientModal" tabindex="-1" role="dialog" aria-labelledby="createClientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="createClientModalLabel">
                    <i class="fas fa-user-plus"></i> Add New Client
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color:white;">&times;</span>
                </button>
            </div>
            <form id="createClientForm">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Name -->
                            <div class="form-group">
                                <label for="new_client_name">Name</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_name" name="name" placeholder="Enter Client Name" required>
                                </div>
                            </div>
                            <!-- Company -->
                            <div class="form-group">
                                <label for="new_client_company">Company</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_company" placeholder="Enter Company Name" name="company">
                                </div>
                            </div>
                            <!-- Phone -->
                            <div class="form-group">
                                <label for="new_client_phone">Phone</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_phone" placeholder="Enter Phone No" name="phone">
                                </div>
                            </div>
                            <!-- Email -->
                            <div class="form-group">
                                <label for="new_client_email">Email</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email" class="form-control" id="new_client_email" placeholder="Enter Client Email"  name="email">
                                </div>
                            </div>
                            <!-- Address -->
                            <div class="form-group">
                                <label for="new_client_address">Address</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_address" placeholder="Enter Address" name="address">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <!-- City -->
                            <div class="form-group">
                                <label for="new_client_city">City</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-city"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_city" placeholder="Enter City" name="city">
                                </div>
                            </div>
                            <!-- Region -->
                            <div class="form-group">
                                <label for="new_client_region">Region</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_region" placeholder="Enter Region" name="region">
                                </div>
                            </div>
                            <!-- Country -->
                            <div class="form-group">
                                <label for="new_client_country">Country</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-flag"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_country" placeholder="Enter Country" name="country">
                                </div>
                            </div>
                            <!-- Post Box -->
                            <div class="form-group">
                                <label for="new_client_postbox">Post Box</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-inbox"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_postbox" placeholder="Enter Post Box" name="postbox">
                                </div>
                            </div>
                            <!-- TAX ID -->
                            <div class="form-group">
                                <label for="new_client_taxid">TAX ID</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-receipt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_taxid" placeholder="Enter Transaction ID" name="taxid">
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