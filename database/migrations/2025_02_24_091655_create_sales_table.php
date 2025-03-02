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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade'); 
            $table->string('invoice_no')->unique(); 
            $table->date('invoice_date'); 
            $table->decimal('subtotal', 10, 2); 
            $table->decimal('discount', 10, 2); 
            $table->decimal('total', 10, 2); 

            // $table->decimal('total_amount', 15, 2); // Total sale amount
            $table->decimal('paid_amount', 15, 2)->default(0); // Amount already paid
            $table->decimal('remaining_amount', 15, 2)->default(0); // Amount left to be paid
            $table->enum('status', ['pending', 'paid', 'partially_paid'])->default('pending'); // Sale status
            
            $table->text('description')->nullable();
            $table->timestamps();    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
