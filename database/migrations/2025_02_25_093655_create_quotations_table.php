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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade'); 
            $table->string('invoice_no')->unique(); 
            $table->date('invoice_date'); 
            $table->decimal('subtotal', 10, 2); 
            $table->decimal('discount', 10, 2); 
            $table->decimal('total', 10, 2); 
            $table->text('description')->nullable();
            $table->date('form_date')->nullable(); 
            $table->date('to_date')->nullable();  
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
