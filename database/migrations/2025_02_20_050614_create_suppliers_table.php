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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('designation')->nullable();
            $table->string('company')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique()->nullable();
            $table->text('address')->nullable();
            $table->string('region')->nullable();
            $table->string('zip')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('postbox')->nullable();
            $table->string('bin')->nullable();
            $table->string('taxid')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_routing_number')->nullable();
            $table->string('password')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=>Active, 0=>Inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
