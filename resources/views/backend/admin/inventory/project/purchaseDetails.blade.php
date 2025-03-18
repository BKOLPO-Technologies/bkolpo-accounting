<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchase->purchaseProducts as $product)
                <tr>
                    <td>{{ $product->product->name }}</td>
                    <td>{{ $product->quantity }}</td>
                    <td>{{ number_format($product->price, 2) }}</td>
                    <td>{{ number_format($product->quantity * $product->price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
