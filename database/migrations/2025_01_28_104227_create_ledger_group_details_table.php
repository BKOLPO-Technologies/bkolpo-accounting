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
        Schema::create('ledger_group_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ledger_id');
            $table->unsignedBigInteger('group_id');
            $table->foreign('ledger_id')->references('id')->on('ledgers')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('ledger_groups')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledger_group_details');
    }
};
