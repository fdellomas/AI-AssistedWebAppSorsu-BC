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
        Schema::create('query_log_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('query_log_id')->constrained('query_logs')->cascadeOnDelete();
            $table->foreignId('answer_sheet_id')->constrained('answer_sheets')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('query_log_items');
    }
};
