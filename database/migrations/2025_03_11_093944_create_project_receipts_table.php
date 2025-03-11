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
        Schema::create('project_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('cascade'); // Customer Payments
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
        Schema::dropIfExists('project_receipts');
    }
};
