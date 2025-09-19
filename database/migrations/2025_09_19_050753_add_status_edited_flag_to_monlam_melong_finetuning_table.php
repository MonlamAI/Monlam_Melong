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
        Schema::table('monlam_melong_finetuning', function (Blueprint $table) {
            $table->boolean('status_edited_in_window')->default(false)->after('status_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monlam_melong_finetuning', function (Blueprint $table) {
            $table->dropColumn('status_edited_in_window');
        });
    }
};
