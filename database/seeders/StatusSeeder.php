<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            'Не начат',
            'В процессе',
            'Завершён',
            'Просрочен',
            'Прерван',
        ];

        foreach ($statuses as $name) {
            Status::firstOrCreate(['name' => $name]);
        }
    }
}
