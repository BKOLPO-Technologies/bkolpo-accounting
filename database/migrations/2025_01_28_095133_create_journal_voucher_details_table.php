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
        Schema::create('journal_voucher_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_voucher_id')->constrained()->onDelete('cascade');
            $table->foreignId('ledger_id')->constrained()->onDelete('cascade');
            $table->string('reference_no')->nullable();
            $table->text('description')->nullable();
            $table->decimal('debit', 15, 2)->default(0.00);
            $table->decimal('credit', 15, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_voucher_details');
    }
};
