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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('test_id')->constrained()->onDelete('cascade');
            $table->integer('score')->nullable()->comment('Баллы за тест');
            $table->integer('percentage')->nullable()->comment('Процент правильных ответов');
            $table->boolean('passed')->default(false)->comment('Прошел ли пользователь тест');
            $table->dateTime('attempted_at')->useCurrent()->comment('Дата и время прохождения');
            $table->integer('duration')->nullable()->comment('Длительность прохождения в минутах');
            $table->timestamps();

            $table->unique(['user_id', 'test_id', 'attempted_at'], 'unique_result_attempt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
