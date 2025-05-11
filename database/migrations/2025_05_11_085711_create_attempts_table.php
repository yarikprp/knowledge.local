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
        Schema::create('attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('test_id')->constrained()->onDelete('cascade');
            $table->integer('total_questions');
            $table->integer('passing_score');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('finish_at')->nullable();
            $table->integer('score')->nullable();
            $table->boolean('is_passed')->default(false);
            $table->foreignId('status_id')->nullable()->constrained('statuses')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attempts');
    }
};
