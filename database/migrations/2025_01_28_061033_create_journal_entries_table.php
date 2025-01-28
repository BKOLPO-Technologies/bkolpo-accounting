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
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_no')->unique();   
            $table->string('reference_no')->unique()->nullable();   
            $table->unsignedBigInteger('account_id')->nullable();  
            $table->timestamp('date');          
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
        Schema::dropIfExists('journal_entries');
    }
};
