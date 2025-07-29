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
            $table->text('reference')->nullable()->after('context');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monlam_melong_finetuning', function (Blueprint $table) {
            $table->dropColumn('reference');
        });
    }
};
