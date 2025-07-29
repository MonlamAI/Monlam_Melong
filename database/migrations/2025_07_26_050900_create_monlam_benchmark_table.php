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
        Schema::create('monlam_benchmark', function (Blueprint $table) {
            $table->id();
            $table->string('subject')->nullable(false);
            $table->string('question_type')->nullable(false);
            $table->text('question_text')->nullable(false);
            $table->text('answer_option1')->nullable(false);
            $table->text('answer_option2')->nullable(false);
            $table->text('answer_option3')->nullable(false);
            $table->text('answer_option4')->nullable(false);
            $table->text('correct_answer')->nullable(false);
            $table->text('explanation')->nullable();
            $table->string('difficulty_level')->default('medium');
            $table->string('category')->nullable();
            $table->text('tags')->nullable();
            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('monlam_benchmark');
    }
};
