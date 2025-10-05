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
        Schema::create('salary_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->decimal('basic', 10, 2)->default(0);
            $table->decimal('hra', 10, 2)->default(0);
            $table->decimal('medical', 10, 2)->default(0);
            $table->decimal('conveyance', 10, 2)->default(0);
            $table->decimal('pf', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('other_deduction', 10, 2)->default(0);
            $table->decimal('gross', 10, 2)->default(0);
            $table->decimal('net', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_structures');
    }
};
