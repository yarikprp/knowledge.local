<?php

namespace Database\Seeders;

use App\Models\EventType;
use Illuminate\Database\Seeder;

class EventTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Тестирование',
            'Обучение',
            'Вебинар',
            'Встреча',
            'Дедлайн',
        ];

        foreach ($types as $name) {
            EventType::firstOrCreate(['name' => $name]);
        }
    }
}
