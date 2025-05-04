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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('test_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_type_id')->constrained();
            $table->text('question_text');
            $table->integer('points')->default(1);
            $table->integer('order')->default(0)->comment('Порядок отображения');
            $table->timestamps();
        });

        Schema::create('question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->string('text');
            $table->timestamps();
        });

        Schema::create('question_correct_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->string('text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_correct_answers');
        Schema::dropIfExists('question_options');
        Schema::dropIfExists('questions');
    }
};
