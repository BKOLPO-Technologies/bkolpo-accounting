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
                            <!-- Title -->
                            <div class="form-group">
                                <label for="new_client_name">Company Name</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_name" name="name" placeholder="Enter Client Name" required>
                                </div>
                            </div>
                            <!-- Company -->
                            <div class="form-group">
                                <label for="new_client_company">Group Name</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_company" placeholder="Enter Company Group Name" name="company">
                                </div>
                            </div>
                            <!-- Contact Person -->
                            <div class="form-group">
                                <label for="new_client_contact_person">Contact Person</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_contact_person" placeholder="Contact Person Name" name="title">
                                </div>
                            </div>
                            <!-- Designation -->
                            <div class="form-group">
                                <label for="new_client_contact_person_designation">Designation</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_contact_person_designation" placeholder="Contact Person Designation" name="designation">
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
                            
                        </div>
                        
                        <div class="col-md-6">
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
                            <!-- City -->
                            {{-- <div class="form-group">
                                <label for="new_client_city">City</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-city"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_city" placeholder="Enter City" name="city">
                                </div>
                            </div> --}}
                            <!-- Zip No -->
                            {{-- <div class="form-group">
                                <label for="new_client_zip">Zip no</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_zip" placeholder="Enter Zip No" name="zip">
                                </div>
                            </div> --}}
                            <!-- Country -->
                            {{-- <div class="form-group">
                                <label for="new_client_country">Country</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-flag"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_country" placeholder="Enter Country" name="country">
                                </div>
                            </div> --}}
                            <div class="form-group">
                                <label>Bank Account Name</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="bank_account_name" name="bank_account_name" placeholder="Enter Bank Account Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Bank Account Number</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="bank_account_number" name="bank_account_number" placeholder="Enter Bank Account Number">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Routing Number</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="bank_routing_number" name="bank_routing_number" placeholder="Enter Roting Number">
                                </div>
                            </div>
                            <!-- Post Box -->
                            <div class="form-group">
                                <label for="new_client_bin">BIN Number</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-inbox"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="new_client_bin" placeholder="Enter BIN Number" name="bin">
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