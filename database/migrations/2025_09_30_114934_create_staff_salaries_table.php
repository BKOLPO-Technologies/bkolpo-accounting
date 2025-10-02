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
        Schema::create('staff_salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained()->onDelete('cascade');
            $table->foreignId('ledger_id')->constrained('ledgers')->onDelete('cascade');
            $table->date('salary_month'); 
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('hra', 10, 2)->default(0);
            $table->decimal('medical', 10, 2)->default(0);
            $table->decimal('conveyance', 10, 2)->default(0);
            $table->decimal('pf', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('other_deductions', 10, 2)->default(0);
            $table->decimal('gross_salary', 10, 2);
            $table->decimal('net_salary', 10, 2);
            $table->decimal('payment_amount', 10, 2);
            $table->enum('payment_method', ['cash', 'bank']);

            $table->string('payment_mood')->nullable(); 
            $table->string('bank_account_no')->nullable();
            $table->string('cheque_no')->nullable();
            $table->date('cheque_date')->nullable();
            $table->string('bank_batch_no')->nullable();
            $table->date('bank_date')->nullable();
            $table->string('bkash_number')->nullable();
            $table->date('bkash_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->text('note')->nullable();
            
            $table->enum('status', ['Pending', 'Approved', 'Unpaid','partial_paid', 'Paid', 'Hold', 'Rejected'])->default('Pending');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_salaries');
    }
};
