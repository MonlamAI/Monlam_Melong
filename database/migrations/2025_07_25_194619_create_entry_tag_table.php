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
        Schema::create('entry_tag', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entry_id'); // Foreign key to monlam_melong_finetuning table
            $table->unsignedBigInteger('tag_id'); // Foreign key to tags table
            
            // Foreign key constraints
            $table->foreign('entry_id')
                  ->references('id')
                  ->on('monlam_melong_finetuning')
                  ->onDelete('cascade');
            
            $table->foreign('tag_id')
                  ->references('id')
                  ->on('tags')
                  ->onDelete('cascade');
            
            // Ensure no duplicate tag assignments
            $table->unique(['entry_id', 'tag_id']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entry_tag');
    }
};
