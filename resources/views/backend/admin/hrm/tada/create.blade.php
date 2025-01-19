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
                <li class="breadcrumb-item active" aria-current="page">HR Management / {{ $pageTitle ?? 'N/A' }}</li>
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
                                <a href="{{ route('ta-da.index')}}" class="btn btn-sm btn-danger rounded-0">
                                    <i class="fa-solid fa-arrow-left"></i> Back To List TA/DA
                                </a>
                            </div>
                        </div>
                        <form action="{{ route('ta-da.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="subtotal" id="hiddenSubtotal">
                            <input type="hidden" name="total" id="hiddenTotal">

                            <div class="card-body">
                                <div class="invoice p-3 mb-3">
                                    <!-- Form fields -->
                                    <div class="row mb-3">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="staff_id">Select Staff</label>
                                                <select name="staff_id" id="staff_id" class="form-control select2" required>
                                                    <option value="">Select Staff</option>
                                                    @foreach($staffs as $staff)
                                                        <option value="{{ $staff->id }}">{{ $staff->full_name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('staff_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        {{-- <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="designation">Designation:</label>
                                                <input type="text" class="form-control" name="designation" placeholder="Enter Designation">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="project_name">Project Name:</label>
                                                <input type="text" class="form-control" name="project_name" placeholder="Enter Project Name">
                                            </div>
                                        </div> --}}
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="tada_type">TaDa Type</label>
                                                <select name="tada_type" id="tada_type" class="form-control select2" required>
                                                    <option value="">Select TaDa Type</option>
                                                    @foreach($tadaTypes as $tadaType)
                                                        <option value="{{ $tadaType->id }}">{{ $tadaType->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('tada_type')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="date">Date:</label>
                                                <input type="date" class="form-control" name="date" placeholder="Enter Date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="description">Description:</label>
                                                <textarea name="description" class="form-control" id="description" cols="5" rows="5" placeholder="Enter description"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>

                                    <!-- Add/Remove Rows (Global plus/minus icons) -->
                                    <div class="row mb-1 text-end">
                                        <div class="col-lg-12">
                                            <button type="button" class="btn btn-success mt-4 addRow"><i class="fas fa-plus"></i></button>
                                        </div>
                                    </div>
                                    

                                    <!-- Table for displaying multiple rows -->
                                    <div class="table-responsive">
                                        <table class="table" id="detailsTable">
                                            <thead>
                                                <tr>
                                                    <th>Purpose Name</th>
                                                    <th>Amount</th>
                                                    <th>Remarks</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Dynamic rows will be added here -->
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- File Upload -->
                                    <div class="row mb-3">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label for="files">Upload Files:</label>
                                                <input type="file" class="form-control" name="files[]" multiple id="fileUpload">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Image Preview Section -->
                                    <div class="row mb-3">
                                        <div class="col-lg-12" id="imagePreview">
                                            <!-- Image previews will be shown here -->
                                        </div>
                                    </div>

                                    <!-- Summary Section -->
                                    <div class="row">
                                        <div class="col-6">
                                            <!-- You can add any other information here if needed -->
                                        </div>
                                        <div class="col-6">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <th style="width:50%">Subtotal:</th>
                                                            <td id="subtotal">0</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Total:</th>
                                                            <td id="total">0</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="row">
                                        <div class="col-12 text-end mt-3">
                                            <button type="submit" class="btn btn-success"> <i class="fas fa-plus"></i> Submit</button>
                                        </div>
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
    document.addEventListener('DOMContentLoaded', function () {
        let rowIndex = 1;

        // Add new row functionality (Global)
        document.querySelector('.addRow').addEventListener('click', function () {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td><input type="text" class="form-control" name="purpose[]" placeholder="Enter Purpose"></td>
                <td><input type="number" class="form-control amount" name="amount[]" placeholder="Enter Amount"></td>
                <td><textarea class="form-control" name="remarks[]" placeholder="Enter Remarks"></textarea></td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger removeRow"><i class="fas fa-minus"></i></button>
                </td>
            `;
            document.querySelector('#detailsTable tbody').appendChild(row);

            // Update the totals
            updateTotals();
        });

        // Add new row functionality (Row-specific)
        document.querySelector('#detailsTable').addEventListener('click', function (e) {
            // Handle adding a new row in the table (inside the specific row)
            if (e.target.classList.contains('addRow')) {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td><input type="text" class="form-control" name="purpose[]" placeholder="Enter Purpose"></td>
                    <td><input type="number" class="form-control amount" name="amount[]" placeholder="Enter Amount"></td>
                    <td><textarea class="form-control" name="remarks[]" placeholder="Enter Remarks"></textarea></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger removeRow"><i class="fas fa-minus"></i></button>
                    </td>
                `;
                e.target.closest('tr').after(row);  // Insert after the current row

                // Update the totals
                updateTotals();
            }

            // Handle removing a row
            if (e.target.classList.contains('removeRow')) {
                e.target.closest('tr').remove();
                updateTotals();
            }
        });

        // Update totals on amount input
        function updateTotals() {
            const amounts = document.querySelectorAll('.amount');
            let subtotal = 0;
            amounts.forEach(function (amount) {
                if (amount.value) {
                    subtotal += parseFloat(amount.value);
                }
            });

            // Update displayed totals
            document.getElementById('subtotal').textContent = subtotal.toFixed(2);
            document.getElementById('total').textContent = subtotal.toFixed(2);

            // Update hidden inputs
            document.getElementById('hiddenSubtotal').value = subtotal.toFixed(2);
            document.getElementById('hiddenTotal').value = subtotal.toFixed(2);
        }

        // Event listener for keyup on amount fields to update the totals
        document.querySelector('#detailsTable').addEventListener('input', function (e) {
            if (e.target.classList.contains('amount')) {
                updateTotals();
            }
        });

        // Handle multiple file upload and show images with close icons
        document.getElementById('fileUpload').addEventListener('change', function (e) {
            const files = e.target.files;
            const imagePreviewContainer = document.getElementById('imagePreview');
            imagePreviewContainer.innerHTML = ''; // Clear any existing images
            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const imgContainer = document.createElement('div');
                    imgContainer.style.position = 'relative'; // Make the container relative for positioning the close icon
                    imgContainer.style.display = 'inline-block';
                    imgContainer.style.margin = '5px';
                    
                    imgContainer.innerHTML = `
                        <img src="${e.target.result}" style="max-width: 100px; margin: 5px;" />
                        <button type="button" class="btn btn-sm btn-danger closeImage" style="position: absolute; top: 0; right: 0; background-color: rgba(255, 0, 0, 0.5); color: white; border-radius: 50%; border: none; padding: 5px;">
                            &times;
                        </button>
                    `;
                    imagePreviewContainer.appendChild(imgContainer);

                    // Close icon functionality to remove image preview
                    imgContainer.querySelector('.closeImage').addEventListener('click', function () {
                        // Remove image preview
                        imgContainer.remove();

                        // Remove the file from the input
                        const inputFiles = Array.from(e.target.files);
                        const index = inputFiles.indexOf(file);
                        if (index !== -1) {
                            inputFiles.splice(index, 1); // Remove the file from the list
                            e.target.files = new DataTransfer().files; // Create a new DataTransfer object
                            inputFiles.forEach(file => {
                                const dt = new DataTransfer();
                                dt.items.add(file);
                                e.target.files = dt.files; // Update the file input value
                            });
                        }
                    });
                };
                reader.readAsDataURL(file);
            });
        });
    });
</script>
@endpush
