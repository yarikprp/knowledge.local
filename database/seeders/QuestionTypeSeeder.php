<?php

namespace Database\Seeders;

use App\Models\QuestionType;
use Illuminate\Database\Seeder;

class QuestionTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Один правильный ответ',      'slug' => 'single'],
            ['name' => 'Несколько правильных ответов', 'slug' => 'multiple'],
            ['name' => 'Текстовый ответ',              'slug' => 'text'],
        ];

        foreach ($types as $type) {
            QuestionType::firstOrCreate(['slug' => $type['slug']], $type);
        }
    }
}
