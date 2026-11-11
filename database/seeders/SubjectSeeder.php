<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            [
                'name'        => 'Информационная безопасность',
                'description' => 'Основы защиты информации, работа с персональными данными, парольная политика, фишинг и социальная инженерия.',
            ],
            [
                'name'        => 'Управление проектами',
                'description' => 'Методологии управления проектами: Agile, Scrum, Kanban, PMBOK. Планирование, риски, ресурсы.',
            ],
            [
                'name'        => 'Охрана труда',
                'description' => 'Требования к безопасным условиям труда, пожарная безопасность, первая помощь, электробезопасность.',
            ],
            [
                'name'        => 'Корпоративная культура',
                'description' => 'Ценности компании, кодекс делового поведения, этика общения с клиентами и коллегами.',
            ],
            [
                'name'        => 'Трудовое законодательство',
                'description' => 'Трудовой кодекс РФ, права и обязанности работников, дисциплинарная ответственность.',
            ],
        ];

        foreach ($subjects as $data) {
            Subject::firstOrCreate(['name' => $data['name']], $data);
        }
    }
}
