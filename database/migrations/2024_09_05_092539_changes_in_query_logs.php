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
        Schema::table('query_logs', function (Blueprint $table) {
            if (Schema::hasColumn('query_logs', 'answer_sheet_id')) {
                $table->dropForeign(['answer_sheet_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('query_logs', function (Blueprint $table) {
            //
        });
    }
};
