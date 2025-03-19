<div class="modal fade" id="createBranchModal" tabindex="-1" role="dialog" aria-labelledby="createBranchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="createBranchModalLabel">
                    <i class="fas fa-user-plus"></i> Add New Branch
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color:white;">&times;</span>
                </button>
            </div>
            <form id="createBranchForm">
                <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label for="name" class="form-label">Name
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-user"></i></span>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter Branch Name">
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="location" class="form-label">Location
                                @error('location')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-map-marker"></i></span>
                                <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}" placeholder="Enter Branch Location">
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
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
                        <div class="col-12 mb-1">
                            <label for="description" class="form-label">Description
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fa fa-pencil-alt"></i></span>
                                <textarea class="form-control" name="description" rows="3" placeholder="Enter description"></textarea>
                            </div>
                        </div>
                    </div>  
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Branch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>