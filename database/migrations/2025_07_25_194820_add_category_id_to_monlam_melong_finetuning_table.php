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
            // Add category_id column and make it nullable initially for data migration
            $table->unsignedBigInteger('category_id')->nullable()->after('category');
            
            // Add foreign key constraint
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('set null'); // If a category is deleted, set to null rather than deleting entries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monlam_melong_finetuning', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['category_id']);
            
            // Then drop the column
            $table->dropColumn('category_id');
        });
    }
};
