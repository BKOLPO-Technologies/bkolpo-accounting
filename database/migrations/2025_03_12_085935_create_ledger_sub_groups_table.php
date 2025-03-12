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
        Schema::create('ledger_sub_groups', function (Blueprint $table) {
            $table->id();
            $table->string('subgroup_name');
            $table->foreignId('ledger_group_id')->constrained('ledger_groups')->onDelete('cascade'); 
            $table->tinyInteger('status')->nullable()->default(1)->comment('1 => Active, 0 => Inactive');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledger_sub_groups');
    }
};
