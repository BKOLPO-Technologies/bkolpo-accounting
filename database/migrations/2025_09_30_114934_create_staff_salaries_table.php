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
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->foreignId('ledger_id')->nullable()->constrained('ledgers')->onDelete('cascade');
            $table->string('voucher_no')->unique()->nullable();
            $table->date('salary_month');
            $table->decimal('basic', 10, 2)->default(0);
            $table->decimal('hra', 10, 2)->default(0);
            $table->decimal('medical', 10, 2)->default(0);
            $table->decimal('conveyance', 10, 2)->default(0);
            $table->decimal('pf', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('other_deduction', 10, 2)->default(0);
            $table->decimal('gross', 10, 2)->default(0);
            $table->decimal('net', 10, 2)->default(0);
            $table->decimal('payment_amount', 10, 2)->default(0);
            $table->enum('payment_method', ['Cash', 'Bank'])->nullable();
            $table->enum('status', ['pending','approved', 'unpaid','partial_paid', 'paid', 'hold', 'rejected'])->default('pending');
            $table->date('payment_date')->nullable();
            $table->text('note')->nullable();
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
