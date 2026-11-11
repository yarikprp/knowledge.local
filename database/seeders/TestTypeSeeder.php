<?php

namespace Database\Seeders;

use App\Models\TestType;
use Illuminate\Database\Seeder;

class TestTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Аттестация',
            'Проверочный тест',
            'Обучающий тест',
            'Экзамен',
        ];

        foreach ($types as $name) {
            TestType::firstOrCreate(['name' => $name]);
        }
    }
}
