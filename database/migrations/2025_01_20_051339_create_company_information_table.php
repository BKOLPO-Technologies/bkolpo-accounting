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
        Schema::create('company_information', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->nullable();
            $table->string('company_branch')->nullable();
            $table->string('company_type')->nullable();

            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->string('invoice_header')->nullable();

            $table->string('country')->nullable();
            $table->string('address')->nullable();
            $table->string('address_optional')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('post_code')->nullable();
            $table->unsignedBigInteger('stock_warning')->nullable();
            $table->string('currency_symbol')->nullable();
            $table->string('sms_api_code')->nullable();
            $table->string('sms_sender_id')->nullable();
            $table->string('sms_provider')->nullable();
            $table->string('sms_setting')->nullable();
            $table->string('fiscal_year')->nullable();
            $table->decimal('vat', 10, 2)->default(0)->nullable();
            $table->decimal('tax', 10, 2)->default(0)->nullable();

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->string('deleted_by')->nullable();
            $table->tinyInteger('status')->nullable()->default(0)->comment('1=>Active, 0=>Inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_information');
    }
};
