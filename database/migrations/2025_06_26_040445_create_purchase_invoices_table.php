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
        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->date('invoice_date');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('purchase_id')->constrained('purchases')->onDelete('cascade');
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total_netamount', 10, 2)->default(0)->nullable();
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('vat_rate', 5, 2)->default(0);
            $table->decimal('vat_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->decimal('grand_total', 10, 2)->nullable(); 
            $table->text('description')->nullable();
            // $table->enum('status', ['draft', 'completed', 'cancelled'])->default('completed');
            $table->decimal('paid_amount', 15, 2)->default(0);
            $table->enum('status', ['pending', 'paid', 'partially_paid'])->default('pending'); 
            $table->boolean('is_referenced')->default(false);
            $table->softDeletes();
            $table->timestamps();
            
            $table->index('invoice_no');
            $table->index('supplier_id');
            $table->index('project_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_invoices');
    }
};
