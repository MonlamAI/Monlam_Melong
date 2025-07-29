<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we'll add a nullable column first
        Schema::table('monlam_melong_finetuning', function (Blueprint $table) {
            $table->text('context')->nullable()->after('answer');
        });

        // Set default values for existing records
        DB::table('monlam_melong_finetuning')
            ->whereNull('context')
            ->update(['context' => 'No context provided']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monlam_melong_finetuning', function (Blueprint $table) {
            $table->dropColumn('context');
        });
    }
};
