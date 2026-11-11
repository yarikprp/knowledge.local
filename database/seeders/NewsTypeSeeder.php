<?php

namespace Database\Seeders;

use App\Models\NewsType;
use Illuminate\Database\Seeder;

class NewsTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Объявление',
            'Новость',
            'Событие',
            'Обновление системы',
        ];

        foreach ($types as $name) {
            NewsType::firstOrCreate(['name' => $name]);
        }
    }
}
