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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique(); 
            $table->unsignedBigInteger('credit_vendor_id')->nullable();           
            $table->unsignedBigInteger('credit_branch_id')->nullable();           
            $table->unsignedBigInteger('credit_account_id')->nullable();  
            $table->unsignedBigInteger('credit_bank_id')->nullable();  
            $table->unsignedBigInteger('credit_payment_id')->nullable();  
            $table->unsignedBigInteger('debit_vendor_id')->nullable();           
            $table->unsignedBigInteger('debit_branch_id')->nullable();           
            $table->unsignedBigInteger('debit_account_id')->nullable();  
            $table->unsignedBigInteger('debit_bank_id')->nullable();  
            $table->unsignedBigInteger('debit_payment_id')->nullable();  
            $table->timestamp('transaction_date');          
            $table->text('description')->nullable();             
            $table->tinyInteger('status')->default(1)->comment('1=>Journal Voucher, 2=>Credit Vocher, 3=>Debit Vocher');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();     
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
