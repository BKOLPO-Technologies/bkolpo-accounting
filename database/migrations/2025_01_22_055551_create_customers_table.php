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
        Schema::create('customers', function (Blueprint $table) {
            
            $table->id();

            // Billing Address
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->text('address');
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('country')->nullable();
            $table->string('postbox')->nullable();
            $table->string('taxid')->nullable();
            $table->string('customergroup')->nullable();

            // Shipping Address
            $table->string('name_s')->nullable();
            $table->string('phone_s')->nullable();
            $table->string('email_s')->nullable();
            $table->text('address_s')->nullable();
            $table->string('city_s')->nullable();
            $table->string('region_s')->nullable();
            $table->string('country_s')->nullable();
            $table->string('postbox_s')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
