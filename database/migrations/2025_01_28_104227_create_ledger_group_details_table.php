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
            $table->foreignId('ledger_group_id')->constrained('ledger_groups');  // Foreign key to ledger_groups table
            $table->foreignId('ledger_id')->constrained('ledgers');  // Foreign key to ledgers table
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
