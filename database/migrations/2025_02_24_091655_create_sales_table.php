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
            $table->integer('category_id')->nullable();
            $table->integer('project_id')->nullable(); 
            $table->string('invoice_no')->unique(); 
            $table->date('invoice_date'); 
            $table->decimal('subtotal', 10, 2)->default(0)->nullable(); 
            $table->decimal('total_netamount', 10, 2)->default(0)->nullable();
            $table->decimal('discount', 10, 2)->default(0)->nullable();
            $table->decimal('transport_cost', 10, 2)->default(0)->nullable();
            $table->decimal('carrying_charge', 10, 2)->default(0)->nullable();
            $table->decimal('vat', 10, 2)->default(0)->nullable();
            $table->decimal('vat_amount', 10, 2)->default(0)->nullable();
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0)->nullable();
            $table->decimal('total', 10, 2)->nullable(); 
            $table->decimal('grand_total', 10, 2)->nullable(); 

            // $table->decimal('total_amount', 15, 2); // Total sale amount
            $table->decimal('paid_amount', 15, 2)->default(0)->nullable(); // Amount already paid
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
