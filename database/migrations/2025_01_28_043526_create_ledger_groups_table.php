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
        Schema::create('ledger_groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_name');
            $table->tinyInteger('status')->nullable()->default(1)->comment('1 => Active, 0 => Inactive');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->foreignId('ledger_id')->constrained('ledgers') ->onDelete('cascade');
            $table->timestamps();
            
            // Optional: Add an index for better query performance
            $table->index('ledger_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledger_groups');
    }
};
