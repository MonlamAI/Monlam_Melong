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
        Schema::create('monlam_melong_finetuning', function (Blueprint $table) {
            $table->id();
            $table->text('question')->nullable(false);
            $table->text('answer')->nullable(false);
            $table->string('category')->nullable();
            $table->integer('difficulty')->default(1);
            $table->text('tags')->nullable();
            $table->timestamps();
            
            // Create indexes for better performance
            $table->index('category');
            $table->index('difficulty');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monlam_melong_finetuning');
    }
};
