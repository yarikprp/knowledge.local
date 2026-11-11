<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\NewsType;
use App\Models\User;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        $mentor = User::where('email', 'mentor@example.com')->first();

        $announcement = NewsType::where('name', 'Объявление')->first();
        $newsType     = NewsType::where('name', 'Новость')->first();
        $eventType    = NewsType::where('name', 'Событие')->first();
        $updateType   = NewsType::where('name', 'Обновление системы')->first();

        $newsItems = [
            [
                'name'         => 'Добро пожаловать в систему знаний!',
                'content'      => 'Уважаемые коллеги! Рады приветствовать вас в нашей корпоративной системе знаний. Здесь вы найдёте учебные материалы, сможете проходить тесты и отслеживать свой прогресс.',
                'user_id'      => $admin->id,
                'news_type_id' => $announcement->id,
            ],
            [
                'name'         => 'Запуск нового учебного модуля по безопасности',
                'content'      => 'С 1 декабря открывается обязательный модуль по информационной безопасности. Все сотрудники должны пройти тест до 31 декабря. Успешное прохождение — не менее 80% правильных ответов.',
                'user_id'      => $admin->id,
                'news_type_id' => $announcement->id,
            ],
            [
                'name'         => 'Результаты тестирования за ноябрь',
                'content'      => 'Подведены итоги тестирования за ноябрь. Средний балл по отделу составил 87%. Поздравляем лучших сотрудников! Материалы для подготовки доступны в разделе «Учебные материалы».',
                'user_id'      => $mentor->id,
                'news_type_id' => $newsType->id,
            ],
            [
                'name'         => 'Вебинар: Эффективные техники обучения',
                'content'      => 'Приглашаем вас на вебинар «Эффективные техники обучения» 20 ноября в 15:00. Спикер — HR-директор компании. Регистрация в корпоративном портале.',
                'user_id'      => $mentor->id,
                'news_type_id' => $eventType->id,
            ],
            [
                'name'         => 'Обновление системы v2.1',
                'content'      => 'Выпущено обновление системы знаний v2.1. Улучшена производительность, добавлен новый тип вопросов «Соответствие», исправлены мелкие ошибки. Подробности в разделе документации.',
                'user_id'      => $admin->id,
                'news_type_id' => $updateType->id,
            ],
            [
                'name'         => 'Новые материалы по управлению проектами',
                'content'      => 'Добавлены новые учебные материалы по методологии управления проектами (Agile, Scrum, Kanban). Рекомендуем к изучению всем руководителям и старшим специалистам.',
                'user_id'      => $mentor->id,
                'news_type_id' => $newsType->id,
            ],
        ];

        foreach ($newsItems as $item) {
            News::firstOrCreate(
                ['name' => $item['name']],
                $item
            );
        }
    }
}
