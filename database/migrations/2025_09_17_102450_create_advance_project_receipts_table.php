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
        Schema::create('advance_project_receipts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('project_id');
            $table->string('reference_no')->unique();
            $table->decimal('receive_amount', 15, 2);
            $table->string('payment_method'); // cash, bank, bkash, etc
            $table->string('payment_mood'); 
            $table->unsignedBigInteger('ledger_id');

            $table->string('bank_account_no')->nullable();
            $table->string('cheque_no')->nullable();
            $table->date('cheque_date')->nullable();
            $table->string('bank_batch_no')->nullable();
            $table->date('bank_date')->nullable();

            $table->string('bkash_number')->nullable();
            $table->date('bkash_date')->nullable();

            $table->date('payment_date');
            $table->text('note')->nullable();
            
            $table->timestamps();

            // Foreign keys (optional)
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('ledger_id')->references('id')->on('ledgers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advance_project_receipts');
    }
};
