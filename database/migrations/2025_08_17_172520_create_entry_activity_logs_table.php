<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('entry_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('entry_id')->constrained('monlam_melong_finetuning')->cascadeOnDelete();
            $table->enum('action', ['created', 'edited']);
            $table->integer('words_created')->default(0);
            $table->integer('words_edited')->default(0);
            $table->string('category')->nullable();
            $table->timestamp('occurred_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entry_activity_logs');
    }
};
