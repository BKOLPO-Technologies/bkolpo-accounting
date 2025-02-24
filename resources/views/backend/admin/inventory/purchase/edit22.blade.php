@extends('layouts.admin', ['pageTitle' => 'Purchase'])
@section('admin')
<div class="content-wrapper">

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline shadow-lg">
                    
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.purchase.update', $purchase->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="product_ids" id="product_ids">
                            <input type="hidden" name="quantities" id="quantities">
                            <input type="hidden" name="prices" id="prices">

                            <div class="row">
                                <!-- Supplier Select -->

                                <!-- Product Select with Search Feature -->
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label for="product">Product</label>
                                    <div class="input-group">
                                        <select name="products" id="product" class="form-control select2 @error('product') is-invalid @enderror" style="width: 100%;">
                                            <option value="">Select Product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->price }}" data-stock="{{ $product->quantity }}">
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('product')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Invoice No -->
                                <!-- Invoice Date -->
                            </div>

                            <!-- Supplier Details Table -->
                               
                            <!-- Product Table -->
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="product-table" class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Sell Price</th>
                                                    <th>Quantity</th>
                                                    <th>Current Stock</th>
                                                    <th>Subtotal</th>
                                                    <th>Remove</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @if ($purchase->products->isEmpty())
                                                <tr id="no-products-row">
                                                    <td colspan="6" class="text-center">No product found</td>
                                                </tr>
                                            @else

                                                @foreach ($purchase->products as $product)
                                                <tr data-product-id="{{ $product->id }}">
                                                    <td class="col-3">{{ $product->name }}</td>
                                                    <td class="col-2">{{ number_format($product->price, 2) }}</td>
                                                    <td class="col-1">
                                                        <input type="number" class="quantity form-control" value="{{ $product->pivot->quantity }}" min="1"
                                                            data-price="{{ $product->price }}" data-stock="{{ $product->stock }}" oninput="updateRow(this)" />
                                                    </td>
                                                    <td class="current-stock col-2">
                                                        <span class="badge bg-info">{{ $product->stock }}</span>
                                                    </td>
                                                    <td class="subtotal">{{ number_format($product->pivot->quantity * $product->price, 2) }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-danger btn-sm remove-product">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endforeach

                                                @endif
                                                <!-- Dynamic rows will be inserted here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end flex-column align-items-end">
                                <!-- Subtotal -->
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label for="subtotal">Subtotal</label>
                                    <input type="text" id="subtotal" name="subtotal" class="form-control" value="{{ old('subtotal', $subtotal) }}" readonly />
                                </div>

                                <!-- Discount -->
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label for="discount">Discount</label>
                                    <input type="text" id="discount" name="discount" class="form-control" value="{{ old('discount', $purchase->discount ?? 0) }}" oninput="updateTotal()" />
                                </div>

                                <!-- Total -->
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <label for="total">Total</label>
                                    <input type="text" id="total" name="total" class="form-control" value="{{ old('total', $subtotal - ($purchase->discount ?? 0)) }}" readonly />
                                </div>
                            </div>
                            
                            <hr>

                            <div class="row text-right">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i>Update Purchase</button>
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

    $('#product').on('change', function() {
        const selectedOption = $(this).find(':selected');
        const productId = selectedOption.val();
        const productName = selectedOption.data('name');
        const productPrice = parseFloat(selectedOption.data('price'));
        const productStock = parseInt(selectedOption.data('stock'));

        if (!productId) return;

        const row = `
            <tr data-product-id="${productId}">
                <td>${productName}</td>
                <td>${productPrice.toFixed(2)}</td>
                <td><input type="number" class="quantity form-control" value="1" min="1" data-price="${productPrice}" data-stock="${productStock}" oninput="updateRow(this)" /></td>
                <td><span class="badge bg-info">${productStock}</span></td>
                <td class="subtotal">${productPrice.toFixed(2)}</td>
                <td><button type="button" class="btn btn-danger btn-sm remove-product"><i class="fas fa-trash"></i></button></td>
            </tr>
        `;
        
        $('#product-table tbody').append(row);
        updateTotal();
        $(this).val('');
    });

    $('#product-table').on('click', '.remove-product', function() {
        $(this).closest('tr').remove();
        updateTotal();
    });

    function updateRow(input) {
        const row = $(input).closest('tr');
        const price = parseFloat($(input).data('price'));
        const quantity = parseInt($(input).val());
        row.find('.subtotal').text((price * quantity).toFixed(2));
        updateTotal();
    }

    function updateTotal() {
        let subtotal = 0;
        $('#product-table tbody tr').each(function() {
            subtotal += parseFloat($(this).find('.subtotal').text());
        });
        let discount = parseFloat($('#discount').val()) || 0;
        $('#subtotal').val(subtotal.toFixed(2));
        $('#total').val((subtotal - discount).toFixed(2));
    }

    // Initialize product table and searchable select
    let products = [];

    // // Add product to the table
    // $('#product').on('change', function() {
    //     const selectedOption = $(this).find(':selected');
    //     const productName = selectedOption.data('name');
    //     const productPrice = parseFloat(selectedOption.data('price'));
    //     const productStock = parseInt(selectedOption.data('stock'));
    //     const productId = selectedOption.val();

    //     const productRow = `
    //         <tr data-product-id="${productId}">
    //             <td class="col-3">${productName}</td>
    //             <td class="col-2">${productPrice.toFixed(2)}</td>
    //             <td  class="col-1"><input type="number" class="quantity form-control" value="1" min="1" data-price="${productPrice}" data-stock="${productStock}" oninput="updateRow(this)" /></td>
    //             <td class="current-stock col-2">
    //                 <span class="badge bg-info">${productStock}</span>
    //             </td>
    //             <td class="subtotal">${productPrice.toFixed(2)}</td>
    //             <td><button type="button" class="btn btn-danger btn-sm remove-product"><i class="fas fa-trash"></i></button></td>
    //         </tr>
    //     `;

    //     $('#product-table tbody').append(productRow);
    //     updateTotal();

    //     // Hide "No Product Found" row if there are products in the table
    //     $('#no-products-row').hide();

    //     // Reset product select
    //     $(this).val('');

    //     // Add the product to the hidden fields
    //     addToHiddenFields(productId, 1, productPrice);
    // });

    // Function to add selected product to hidden fields
    function addToHiddenFields(productId, quantity, price) {
        let productIds = $('#product_ids').val() ? $('#product_ids').val().split(',') : [];
        let quantities = $('#quantities').val() ? $('#quantities').val().split(',') : [];
        //alert(quantities);
        let prices = $('#prices').val() ? $('#prices').val().split(',') : [];

        // Add product details to arrays
        //console.log("productId = ", productId);
        productIds.push(productId);
        console.log("quantity = ", quantity);
        quantities.push(quantity);
        prices.push(price);

        // Update hidden fields with the new values
        $('#product_ids').val(productIds.join(','));
        $('#quantities').val(quantities.join(','));
        $('#prices').val(prices.join(','));
    }

    // // Remove product from table and hidden fields
    // $('#product-table').on('click', '.remove-product', function() {
    //     const row = $(this).closest('tr');
    //     const productId = row.find('input[type="number"]').data('product-id');
    //     const quantity = row.find('input[type="number"]').val();
    //     const price = row.find('.subtotal').text();

    //     // Remove product details from hidden fields
    //     removeFromHiddenFields(productId, quantity, price);

    //     // Remove the row from the table
    //     row.remove();

    //     // Show "No Product Found" row if table is empty
    //     if ($('#product-table tbody tr').length === 0) {
    //         $('#no-products-row').show();
    //     }

    //     updateTotal();
    // });

    // Function to remove product from hidden fields
    function removeFromHiddenFields(productId, quantity, price) {
        let productIds = $('#product_ids').val().split(',');
        let quantities = $('#quantities').val().split(',');
        let prices = $('#prices').val().split(',');

        // Find the index of the product to remove
        const index = productIds.indexOf(productId);

        if (index !== -1) {
            productIds.splice(index, 1);
            quantities.splice(index, 1);
            prices.splice(index, 1);
        }

        // Update hidden fields with the new values
        $('#product_ids').val(productIds.join(','));
        $('#quantities').val(quantities.join(','));
        $('#prices').val(prices.join(','));

    }

    // // Update row subtotal when quantity changes
    // function updateRow(input) {
    //     const row = $(input).closest('tr');
    //     const price = parseFloat($(input).data('price'));
    //     const quantity = parseInt($(input).val());
    //     const stock = parseInt($(input).data('stock'));

    //     if (quantity > stock) {
    //         // Display toastr alert
    //         toastr.error('Quantity cannot exceed available stock.', 'Stock Limit Exceeded', {
    //             closeButton: true,
    //             progressBar: true,
    //             timeOut: 5000
    //         });

    //         $(input).val(stock);  // Reset to stock value
    //     }

    //     const subtotal = price * quantity;
    //     row.find('.subtotal').text(subtotal.toFixed(2));

    //     // Update the hidden fields
    //     updateHiddenFields();
         
    //     updateTotal();
    // }

    // Function to update hidden fields when quantity changes
    function updateHiddenFields() {
        let productIds = [];
        let quantities = [];
        let prices = [];

        $('#product-table tbody tr').each(function() {
            const row = $(this);
            const productId = row.data('product-id');  // Get product ID from <tr>
            const quantity = row.find('.quantity').val();
            const price = row.find('.quantity').data('price');

            // Debugging logs
            console.log("Row Data:", row.html());  // Log entire row structure
            console.log("Extracted productId:", productId);
            console.log("Extracted quantity:", quantity);
            console.log("Extracted price:", price);

            // if (productId) {
            if (productId !== undefined) { // Ensure productId is valid
                productIds.push(productId);
                quantities.push(quantity);
                prices.push(price);
            }
        });

        // Update the hidden fields
        $('#product_ids').val(productIds.join(','));
        $('#quantities').val(quantities.join(','));
        $('#prices').val(prices.join(','));
    }

    // // Calculate the subtotal, discount, and total
    // function updateTotal() {
    //     let subtotal = 0;

    //     $('#product-table tbody tr').each(function() {
    //         const rowSubtotal = parseFloat($(this).find('.subtotal').text());
    //         if (!isNaN(rowSubtotal)) {
    //             subtotal += rowSubtotal;
    //         }
    //     });

    //     // Get discount and handle invalid input
    //     const discount = parseFloat($('#discount').val());
    //     const validDiscount = isNaN(discount) ? 0 : discount;

    //     const total = subtotal - validDiscount;

    //     $('#subtotal').val(subtotal.toFixed(2));
    //     $('#total').val(total.toFixed(2));
    // }
    
</script>
@endpush
