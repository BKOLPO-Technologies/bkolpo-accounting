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
        Schema::create('ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('opening_balance', 15, 2)->nullable()->default(0); 
            $table->enum('ob_type', ['debit', 'credit'])->nullable();
            $table->enum('type', ['Bank', 'Cash', 'Receivable', 'Payable','Purchases','Sales'])->nullable();
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
        Schema::dropIfExists('ledgers');
    }
};
