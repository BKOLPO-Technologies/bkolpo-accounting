<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');
            $table->string('project_location');
            $table->string('project_coordinator');
            // $table->unsignedBigInteger('client_id'); // Foreign key
            $table->string('reference_no')->unique();
            $table->date('schedule_date')->nullable();
            $table->decimal('total_discount', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('transport_cost', 10, 2)->default(0);
            $table->decimal('carrying_charge', 10, 2)->default(0);
            $table->decimal('vat', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);
            $table->decimal('paid_amount', 15, 2)->default(0); // Amount already paid
            $table->enum('status', ['pending', 'paid', 'partially_paid'])->default('pending'); // Project status
            $table->enum('project_type', ['ongoing', 'upcoming', 'completed'])->default('ongoing'); // Project Type
            $table->text('description')->nullable();
            $table->longText('terms_conditions')->nullable();
            // $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
