<div class="invoice p-3 mb-3">
    <div class="row">
        <div class="col-12">
            <h4>
                <i class="fas fa-globe"></i> Bkolpo, Technology.
                <small class="float-right">{{ now()->format('d M Y') }}</small>
            </h4>
        </div>
    </div>
    <hr>
    <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
            <strong>Supplier:</strong><br>
            {{ $purchase->supplier->name }}<br>
            {{ $purchase->supplier->address }}, {{ $purchase->supplier->city }}<br>
            Phone: {{ $purchase->supplier->phone }}<br>
            Email: {{ $purchase->supplier->email }}
        </div>
        <div class="col-sm-4 invoice-col">
            <b>Invoice #{{ $purchase->invoice_no }}</b><br>
            Date: {{ \Carbon\Carbon::parse($purchase->invoice_date)->format('d F Y') }}
        </div>
    </div>
    <br>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($purchase->products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ number_format($product->pivot->price, 2) }}</td>
                        <td>{{ $product->pivot->quantity }}</td>
                        <td>{{ number_format($product->pivot->price * $product->pivot->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
