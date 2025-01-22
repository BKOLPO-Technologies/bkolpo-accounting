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
            $table->string('title');
            $table->string('status')->default('Waiting');
            $table->string('priority')->default('Low');
            $table->unsignedBigInteger('customer_id');
            $table->boolean('customerview')->default(true);
            $table->boolean('customercomment')->default(true);
            $table->decimal('budget', 10, 2)->nullable();
            $table->string('phase')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->unsignedInteger('link_to_calendar')->default(0);
            $table->string('color')->nullable();
            $table->text('note')->nullable();
            $table->string('tags')->nullable();
            $table->unsignedInteger('task_communication')->default(0);
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
