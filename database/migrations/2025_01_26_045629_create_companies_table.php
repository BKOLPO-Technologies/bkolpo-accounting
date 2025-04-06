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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('branch_id')->nullable(); 
            $table->string('country')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('post_code')->nullable();
            $table->string('logo')->nullable();
            $table->string('account_no')->nullable();
            $table->decimal('initial_balance', 10, 2)->default(0)->nullable();  
            $table->string('currency_symbol')->nullable();
            $table->string('fiscal_year')->nullable();
            $table->decimal('vat', 10, 2)->default(0)->nullable();
            $table->decimal('tax', 10, 2)->default(0)->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('status')->nullable()->default(1)->comment('1=>Active, 0=>Inactive');
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
        Schema::dropIfExists('companies');
    }
};
