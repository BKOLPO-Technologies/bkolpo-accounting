<!-- Create Project Modal -->
<div class="modal fade" id="createProjectModal" tabindex="-1" role="dialog" aria-labelledby="createProjectModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createProjectModalLabel">Create Project</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('advance.project.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- Project Name -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="project_name">Project Name</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-project-diagram"></i></span>
                                </div>
                                <input type="text" name="project_name" id="project_name"
                                    class="form-control @error('project_name') is-invalid @enderror"
                                    placeholder="Enter Project Name" value="{{ old('project_name') }}" required>
                            </div>
                            @error('project_name')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Project Location -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="project_location">Project Location</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                </div>
                                <input type="text" name="project_location" id="project_location"
                                    class="form-control @error('project_location') is-invalid @enderror"
                                    placeholder="Enter Project Location" value="{{ old('project_location') }}" required>
                            </div>
                            @error('project_location')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Project Coordinator -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="project_coordinator">Project
                                Coordinator</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                </div>
                                <input type="text" name="project_coordinator" id="project_coordinator"
                                    class="form-control @error('project_coordinator') is-invalid @enderror"
                                    placeholder="Enter Project Coordinator" value="{{ old('project_coordinator') }}"
                                    required>
                            </div>
                            @error('project_coordinator')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Client Select -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="customer">Client</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <select name="client_id" id="client_id"
                                    class="form-control select2 @error('client_id') is-invalid @enderror">
                                    <option value="" disabled {{ old('client') ? '' : 'selected' }}>
                                        Select Client</option>
                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}" data-name="{{ $client->name }}"
                                            data-company="{{ $client->company }}" data-phone="{{ $client->phone }}"
                                            data-email="{{ $client->email }}"
                                            {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-danger" type="button" id="addClientBtn" data-toggle="modal"
                                        data-target="#createClientModal">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            @error('client_id')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Reference No -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="referance_no">Reference No</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                </div>
                                <input type="text" id="referance_no" name="reference_no"
                                    class="form-control @error('referance_no') is-invalid @enderror"
                                    value="{{ old('referance_no', $referance_no) }}" readonly />
                            </div>
                            @error('referance_no')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Project Schedule Date -->
                        <div class="col-lg-4 col-md-6 mb-3">
                            <label for="schedule_date">Project Schedule
                                Date</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                </div>
                                <input type="text" id="date" name="schedule_date"
                                    class="form-control @error('schedule_date') is-invalid @enderror"
                                    value="{{ old('schedule_date', now()->format('Y-m-d')) }}" />
                            </div>
                            @error('schedule_date')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <!-- Product Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="product-table" class="table table-bordered">
                                    <table id="product-table" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Items</th>
                                                <th>Specifications</th>
                                                <th>Order Unit</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="product-tbody">
                                            <tr>
                                                <td style="width:20%;">
                                                    <div class="input-group">
                                                        <select class="item-select select2 form-control"
                                                            name="items[]" required>
                                                            <option value="">
                                                                Select Item</option>
                                                            @foreach ($products as $product)
                                                                <option value="{{ $product->id }}"
                                                                    data-description="{{ $product->description }}"
                                                                    data-unit="{{ $product->unit_id }}"
                                                                    data-price="{{ $product->price }}">
                                                                    {{ $product->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div class="input-group-append">
                                                            <button type="button"
                                                                class="btn btn-danger open-product-modal"
                                                                data-toggle="modal" data-target="#createProductModal">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>

                                                    </div>
                                                </td>

                                                <td style="width:15%;">
                                                    {{-- <textarea class="item-description form-control" name="items_description[]" rows="1" cols="2" placeholder="Enter Item Description" required></textarea> --}}
                                                    <input type="text" class="item-description form-control"
                                                        name="items_description[]">
                                                </td>

                                                <td style="width: 11%;">
                                                    <select name="order_unit[]" class="unit-select form-control"
                                                        required>
                                                        <option value="" disabled selected>Select
                                                            Unit</option>
                                                        @foreach ($units as $unit)
                                                            <option value="{{ $unit->id }}">
                                                                {{ $unit->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                <td style="width:15%;">
                                                    <input type="number" name="quantity[]"
                                                        class="form-control quantity" placeholder="Enter Quantity"
                                                        min="1" step="0.01" required>
                                                </td>

                                                <td style="width:15%">
                                                    <input type="number" name="unit_price[]"
                                                        class="form-control unit-price" placeholder="Enter Unit Price"
                                                        min="0" step="0.01" required
                                                        style="text-align: right;">
                                                </td>

                                                <td style="width:15%">
                                                    <input type="text" name="total[]" class="form-control total"
                                                        readonly style="text-align: right;">
                                                </td>

                                                <td class="text-center">
                                                    <button type="button" class="btn btn-success btn-sm add-row"><i
                                                            class="fas fa-plus"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end flex-column align-items-end">
                        <!-- First Row: Subtotal and Total Discount -->
                        <div class="row w-100">
                            <div class="col-12 col-lg-6 mb-2">
                            </div>
                            <div class="col-12 col-lg-6 mb-2">
                                <table class="table table-bordered">
                                    <tbody>
                                        <!-- Subtotal and Discount Row -->
                                        <tr>
                                            <td><label for="subtotal">Total
                                                    Amount</label></td>
                                            <td>
                                                <div class="col-12 col-lg-12">
                                                    <input type="text" id="subtotal" name="total_subtotal"
                                                        class="form-control" value="0" readonly
                                                        style="text-align: right;" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="total_discount">Discount</label>
                                            </td>
                                            <td>
                                                <div class="col-12 col-lg-12">
                                                    <input type="number" id="total_discount" name="total_discount"
                                                        class="form-control" step="0.01"
                                                        placeholder="Enter Discount" style="text-align: right;" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="total_netamount">Net
                                                    Amount</label></td>
                                            <td>
                                                <div class="col-12 col-lg-12">
                                                    <input type="number" id="total_netamount" name="total_netamount"
                                                        class="form-control" readonly placeholder="0.00"
                                                        style="text-align: right;" />
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Include VAT and TAX Checkboxes -->
                                        <tr>
                                            <td>
                                                <div class="icheck-success d-inline">
                                                    <input type="checkbox" name="include_tax" id="include_tax">
                                                    <!-- Include TAX -->
                                                    <label for="include_tax" class="me-3">
                                                        Include TAX (%)
                                                        <input type="number" name="tax" id="tax"
                                                            value="{{ $tax }}" min="0"
                                                            class="form-control form-control-sm d-inline-block"
                                                            step="0.01" placeholder="Enter TAX"
                                                            style="width: 70px; margin-left: 10px;" disabled />
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="col-12 col-lg-12 tax-fields">
                                                    <input type="text" id="tax_amount" name="tax_amount"
                                                        class="form-control" readonly placeholder="TAX Amount"
                                                        style="text-align: right;" />
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="icheck-success d-inline">
                                                    <input type="checkbox" name="include_vat" id="include_vat">
                                                    <label for="include_vat">
                                                        Include VAT (%)
                                                        <input type="number" id="vat" name="vat"
                                                            value="{{ $vat }}" min="0"
                                                            class="form-control form-control-sm vat-input"
                                                            step="0.01" placeholder="Enter VAT"
                                                            style="width: 70px; display: inline-block; margin-left: 10px;"
                                                            disabled />
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="col-12 col-lg-12 vat-fields">
                                                    <input type="text" id="vat_amount" name="vat_amount"
                                                        class="form-control" readonly placeholder="VAT Amount"
                                                        style="text-align: right;" />
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Grand Total Row -->
                                        <tr>
                                            <td><label for="grand_total">Grand
                                                    Total</label></td>
                                            <td>
                                                <div class="col-12 col-lg-12">
                                                    <input type="text" id="grand_total" name="grand_total"
                                                        class="form-control" value="0" readonly
                                                        style="text-align: right;" />
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <!-- Project Type -->
                    <div class="col-lg-12 col-md-12 mb-3">
                        <label for="project_type">Project Type</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-cogs"></i></span>
                                <!-- Example icon -->
                            </div>
                            <select name="project_type" id="project_type"
                                class="form-control @error('project_type') is-invalid @enderror" required>
                                <option value="" disabled>Select Project Type
                                </option>
                                {{-- <option value="ongoing" {{ old('project_type') == 'ongoing' ? 'selected' : '' }}>Ongoing</option> --}}
                                <option value="Running" {{ old('project_type') == 'Running' ? 'selected' : '' }}>
                                    Running</option>
                                <option value="upcoming" {{ old('project_type') == 'upcoming' ? 'selected' : '' }}>
                                    Upcoming</option>
                                <option value="completed" {{ old('project_type') == 'completed' ? 'selected' : '' }}>
                                    Completed</option>
                            </select>
                        </div>
                        @error('project_type')
                            <div class="invalid-feedback">
                                <i class="fas fa-exclamation-circle"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Note -->
                    <div class="col-lg-12 col-md-12 mb-3">
                        <label for="note">Note</label>
                        <textarea id="note" name="description" class="form-control" rows="3" placeholder="Enter Some Note"></textarea>
                    </div>
                    <!-- Terms & Conditions -->
                    <div class="col-lg-12 col-md-12 mb-3">
                        <label for="note">Terms & Conditions</label>
                        <textarea id="summernote" name="terms_conditions" class="form-control" rows="3"
                            placeholder="Enter Terms & Conditions"></textarea>
                    </div>
                    <div class="row text-right">
                        <div class="col-12">
                            <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Add</button>
                        </div>
                    </div>
                </form>
            </div>
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save</button>
            </div> --}}
        </div>
    </div>
</div>
