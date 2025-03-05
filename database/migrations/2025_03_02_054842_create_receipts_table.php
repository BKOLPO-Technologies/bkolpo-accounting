<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ledger_id')->constrained('ledgers')->onDelete('cascade'); // Ledger-wise receipt
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('cascade'); // Customer Payments
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('cascade'); // Supplier Payments
            $table->foreignId('incoming_chalan_id')->nullable()->constrained('incoming_chalans')->onDelete('cascade'); // Chalan-wise receipt for incoming
            $table->foreignId('outcoming_chalan_id')->nullable()->constrained('outcoming_chalans')->onDelete('cascade'); // Chalan-wise receipt for outgoing

            $table->string('invoice_no');
        
            $table->decimal('total_amount', 15, 2);
            $table->decimal('pay_amount', 15, 2);
            $table->decimal('due_amount', 15, 2);
            $table->enum('payment_method', ['cash', 'bank']);
            $table->text('description')->nullable();
            $table->date('payment_date');
            
        
            // Adding a status to distinguish incoming and outgoing receipts
            $table->enum('status', ['incoming', 'outcoming'])->default('incoming'); // Status to differentiate
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
