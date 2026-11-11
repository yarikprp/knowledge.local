<?php

namespace Database\Seeders;

use App\Models\Calendar;
use App\Models\EventType;
use App\Models\Test;
use App\Models\User;
use Illuminate\Database\Seeder;

class CalendarSeeder extends Seeder
{
    public function run(): void
    {
        $testingType  = EventType::where('name', 'Тестирование')->first();
        $learningType = EventType::where('name', 'Обучение')->first();
        $webinarType  = EventType::where('name', 'Вебинар')->first();
        $deadlineType = EventType::where('name', 'Дедлайн')->first();

        $users = User::all();
        $tests = Test::all();

        $events = [
            [
                'event_date'    => now()->addDays(5)->format('Y-m-d'),
                'event_type_id' => $testingType->id,
                'notes'         => 'Аттестация по информационной безопасности',
                'is_notified'   => false,
                'testing_id'    => $tests->firstWhere('slug', 'info-security-basic')?->id,
            ],
            [
                'event_date'    => now()->addDays(7)->format('Y-m-d'),
                'event_type_id' => $webinarType->id,
                'notes'         => 'Вебинар: Эффективные техники обучения (15:00)',
                'is_notified'   => false,
                'testing_id'    => null,
            ],
            [
                'event_date'    => now()->addDays(10)->format('Y-m-d'),
                'event_type_id' => $testingType->id,
                'notes'         => 'Тест по управлению проектами',
                'is_notified'   => false,
                'testing_id'    => $tests->firstWhere('slug', 'project-management-agile')?->id,
            ],
            [
                'event_date'    => now()->addDays(14)->format('Y-m-d'),
                'event_type_id' => $learningType->id,
                'notes'         => 'Изучение материалов по охране труда',
                'is_notified'   => false,
                'testing_id'    => null,
            ],
            [
                'event_date'    => now()->addDays(20)->format('Y-m-d'),
                'event_type_id' => $deadlineType->id,
                'notes'         => 'Дедлайн по прохождению аттестации по ИБ',
                'is_notified'   => false,
                'testing_id'    => $tests->firstWhere('slug', 'info-security-basic')?->id,
            ],
            [
                'event_date'    => now()->subDays(3)->format('Y-m-d'),
                'event_type_id' => $testingType->id,
                'notes'         => 'Пройденное тестирование по корпоративной культуре',
                'is_notified'   => true,
                'testing_id'    => $tests->firstWhere('slug', 'corporate-culture')?->id,
            ],
        ];

        // Создаём события для первых двух пользователей (admin и mentor)
        foreach ($users->take(3) as $user) {
            foreach ($events as $event) {
                Calendar::firstOrCreate(
                    [
                        'user_id'    => $user->id,
                        'event_date' => $event['event_date'],
                        'notes'      => $event['notes'],
                    ],
                    array_merge($event, ['user_id' => $user->id])
                );
            }
        }
    }
}
