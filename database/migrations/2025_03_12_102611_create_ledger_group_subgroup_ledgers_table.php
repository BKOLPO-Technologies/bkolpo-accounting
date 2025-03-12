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
        Schema::create('ledger_group_subgroup_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('ledger_groups')->onDelete('cascade');
            $table->foreignId('sub_group_id')->constrained('ledger_sub_groups')->onDelete('cascade');
            $table->foreignId('ledger_id')->constrained('ledgers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledger_group_subgroup_ledgers');
    }
};
