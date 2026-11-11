<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionCorrectAnswer;
use App\Models\QuestionOption;
use App\Models\QuestionType;
use App\Models\Test;
use App\Models\TestType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        $single   = QuestionType::where('slug', 'single')->first();
        $multiple = QuestionType::where('slug', 'multiple')->first();
        $text     = QuestionType::where('slug', 'text')->first();

        $attestation    = TestType::where('name', 'Аттестация')->first();
        $checkTest      = TestType::where('name', 'Проверочный тест')->first();
        $learningTest   = TestType::where('name', 'Обучающий тест')->first();

        $testsData = [
            [
                'test' => [
                    'name'          => 'Информационная безопасность — базовый курс',
                    'slug'          => 'info-security-basic',
                    'description'   => 'Обязательный тест по информационной безопасности для всех сотрудников. Минимальный проходной балл — 80%.',
                    'test_types_id' => $attestation->id,
                    'start_date'    => now()->subDays(5),
                    'end_date'      => now()->addDays(25),
                    'attempt_limit' => 3,
                    'time_limit'    => 20,
                    'is_active'     => true,
                ],
                'questions' => [
                    [
                        'name'              => 'Минимальная длина пароля',
                        'question_type_id'  => $single->id,
                        'question_text'     => 'Какова минимальная длина пароля согласно парольной политике компании?',
                        'points'            => 1,
                        'order'             => 1,
                        'options' => [
                            ['text' => '6 символов',  'is_correct' => false],
                            ['text' => '8 символов',  'is_correct' => false],
                            ['text' => '12 символов', 'is_correct' => true],
                            ['text' => '16 символов', 'is_correct' => false],
                        ],
                    ],
                    [
                        'name'             => 'Признаки фишингового письма',
                        'question_type_id' => $multiple->id,
                        'question_text'    => 'Выберите все признаки фишингового письма:',
                        'points'           => 2,
                        'order'            => 2,
                        'options' => [
                            ['text' => 'Незнакомый адрес отправителя',       'is_correct' => true],
                            ['text' => 'Срочный призыв ввести пароль',         'is_correct' => true],
                            ['text' => 'Официальный логотип компании',         'is_correct' => false],
                            ['text' => 'Вложение с расширением .exe',          'is_correct' => true],
                            ['text' => 'Обращение по имени и фамилии',         'is_correct' => false],
                        ],
                    ],
                    [
                        'name'             => 'Действие при фишинговом письме',
                        'question_type_id' => $single->id,
                        'question_text'    => 'Что следует сделать при получении подозрительного письма с вложением?',
                        'points'           => 1,
                        'order'            => 3,
                        'options' => [
                            ['text' => 'Открыть вложение, чтобы проверить содержимое',        'is_correct' => false],
                            ['text' => 'Переслать письмо коллегам для проверки',               'is_correct' => false],
                            ['text' => 'Не открывать вложение и сообщить в службу безопасности', 'is_correct' => true],
                            ['text' => 'Удалить письмо и ничего не делать',                    'is_correct' => false],
                        ],
                    ],
                    [
                        'name'             => 'Смена пароля',
                        'question_type_id' => $single->id,
                        'question_text'    => 'Как часто необходимо менять пароль согласно политике компании?',
                        'points'           => 1,
                        'order'            => 4,
                        'options' => [
                            ['text' => 'Раз в 30 дней',  'is_correct' => false],
                            ['text' => 'Раз в 60 дней',  'is_correct' => false],
                            ['text' => 'Раз в 90 дней',  'is_correct' => true],
                            ['text' => 'Раз в 180 дней', 'is_correct' => false],
                        ],
                    ],
                    [
                        'name'             => 'Категория персональных данных',
                        'question_type_id' => $single->id,
                        'question_text'    => 'Что из перечисленного относится к персональным данным?',
                        'points'           => 1,
                        'order'            => 5,
                        'options' => [
                            ['text' => 'Название компании',       'is_correct' => false],
                            ['text' => 'ФИО сотрудника',           'is_correct' => true],
                            ['text' => 'Рыночная цена продукта',   'is_correct' => false],
                            ['text' => 'Адрес офиса компании',     'is_correct' => false],
                        ],
                    ],
                    [
                        'name'             => 'Запрет при работе с паролями',
                        'question_type_id' => $multiple->id,
                        'question_text'    => 'Что ЗАПРЕЩЕНО делать с паролями? (выберите все верные ответы)',
                        'points'           => 2,
                        'order'            => 6,
                        'options' => [
                            ['text' => 'Использовать менеджер паролей',              'is_correct' => false],
                            ['text' => 'Передавать пароль коллегам',                 'is_correct' => true],
                            ['text' => 'Использовать одинаковый пароль везде',       'is_correct' => true],
                            ['text' => 'Записывать пароль на стикер у монитора',     'is_correct' => true],
                            ['text' => 'Регулярно менять пароль',                    'is_correct' => false],
                        ],
                    ],
                    [
                        'name'              => 'Ответственность за утечку данных',
                        'question_type_id'  => $text->id,
                        'question_text'     => 'Укажите статью Уголовного кодекса РФ, предусматривающую ответственность за нарушение неприкосновенности частной жизни (разглашение персональных данных).',
                        'points'            => 2,
                        'order'             => 7,
                        'options'           => [],
                        'correct_answers'   => ['137', 'ст. 137', 'статья 137'],
                    ],
                ],
            ],
            [
                'test' => [
                    'name'          => 'Управление проектами: Agile и Scrum',
                    'slug'          => 'project-management-agile',
                    'description'   => 'Проверочный тест по методологиям гибкого управления проектами.',
                    'test_types_id' => $checkTest->id,
                    'start_date'    => now()->subDays(10),
                    'end_date'      => now()->addDays(20),
                    'attempt_limit' => 2,
                    'time_limit'    => 15,
                    'is_active'     => true,
                ],
                'questions' => [
                    [
                        'name'             => 'Ценности Agile',
                        'question_type_id' => $multiple->id,
                        'question_text'    => 'Какие из перечисленных являются ценностями Agile Manifesto?',
                        'points'           => 2,
                        'order'            => 1,
                        'options' => [
                            ['text' => 'Люди и взаимодействие важнее процессов',   'is_correct' => true],
                            ['text' => 'Максимальная документация всего',           'is_correct' => false],
                            ['text' => 'Работающий продукт важнее документации',   'is_correct' => true],
                            ['text' => 'Следование плану важнее изменений',        'is_correct' => false],
                            ['text' => 'Сотрудничество с заказчиком важнее контрактов', 'is_correct' => true],
                        ],
                    ],
                    [
                        'name'             => 'Роль Scrum Master',
                        'question_type_id' => $single->id,
                        'question_text'    => 'Какова основная обязанность Scrum Master?',
                        'points'           => 1,
                        'order'            => 2,
                        'options' => [
                            ['text' => 'Определять приоритеты в бэклоге',          'is_correct' => false],
                            ['text' => 'Писать код вместе с командой',             'is_correct' => false],
                            ['text' => 'Устранять препятствия и фасилитировать процессы Scrum', 'is_correct' => true],
                            ['text' => 'Общаться с заказчиком напрямую',           'is_correct' => false],
                        ],
                    ],
                    [
                        'name'             => 'Длительность Daily Scrum',
                        'question_type_id' => $single->id,
                        'question_text'    => 'Какова максимальная длительность Daily Scrum?',
                        'points'           => 1,
                        'order'            => 3,
                        'options' => [
                            ['text' => '5 минут',  'is_correct' => false],
                            ['text' => '15 минут', 'is_correct' => true],
                            ['text' => '30 минут', 'is_correct' => false],
                            ['text' => '1 час',    'is_correct' => false],
                        ],
                    ],
                    [
                        'name'             => 'Артефакты Scrum',
                        'question_type_id' => $multiple->id,
                        'question_text'    => 'Выберите все артефакты Scrum:',
                        'points'           => 2,
                        'order'            => 4,
                        'options' => [
                            ['text' => 'Product Backlog',  'is_correct' => true],
                            ['text' => 'Sprint Backlog',   'is_correct' => true],
                            ['text' => 'Increment',        'is_correct' => true],
                            ['text' => 'Gantt Chart',      'is_correct' => false],
                            ['text' => 'Risk Register',    'is_correct' => false],
                        ],
                    ],
                    [
                        'name'             => 'Роль владельца продукта',
                        'question_type_id' => $single->id,
                        'question_text'    => 'Кто в Scrum отвечает за приоритизацию Product Backlog?',
                        'points'           => 1,
                        'order'            => 5,
                        'options' => [
                            ['text' => 'Scrum Master',     'is_correct' => false],
                            ['text' => 'Product Owner',    'is_correct' => true],
                            ['text' => 'Вся команда',      'is_correct' => false],
                            ['text' => 'Руководитель',     'is_correct' => false],
                        ],
                    ],
                ],
            ],
            [
                'test' => [
                    'name'          => 'Охрана труда и пожарная безопасность',
                    'slug'          => 'labor-safety',
                    'description'   => 'Ежегодный обязательный инструктаж по охране труда и пожарной безопасности.',
                    'test_types_id' => $attestation->id,
                    'start_date'    => now()->subDays(2),
                    'end_date'      => now()->addDays(28),
                    'attempt_limit' => 5,
                    'time_limit'    => 10,
                    'is_active'     => true,
                ],
                'questions' => [
                    [
                        'name'             => 'Действия при пожаре',
                        'question_type_id' => $single->id,
                        'question_text'    => 'Что нужно сделать в ПЕРВУЮ ОЧЕРЕДЬ при обнаружении пожара?',
                        'points'           => 2,
                        'order'            => 1,
                        'options' => [
                            ['text' => 'Попытаться потушить огонь самостоятельно',  'is_correct' => false],
                            ['text' => 'Нажать кнопку пожарной сигнализации и позвонить 101', 'is_correct' => true],
                            ['text' => 'Эвакуировать документы',                   'is_correct' => false],
                            ['text' => 'Открыть все окна для проветривания',        'is_correct' => false],
                        ],
                    ],
                    [
                        'name'             => 'Запрет при эвакуации',
                        'question_type_id' => $single->id,
                        'question_text'    => 'Что категорически запрещено при эвакуации из здания при пожаре?',
                        'points'           => 1,
                        'order'            => 2,
                        'options' => [
                            ['text' => 'Пользоваться лестницей',      'is_correct' => false],
                            ['text' => 'Пользоваться лифтом',         'is_correct' => true],
                            ['text' => 'Помогать коллегам',           'is_correct' => false],
                            ['text' => 'Вызывать пожарных',           'is_correct' => false],
                        ],
                    ],
                    [
                        'name'             => 'СЛР: соотношение нажатий и вдохов',
                        'question_type_id' => $single->id,
                        'question_text'    => 'Каково правильное соотношение нажатий на грудную клетку к вдохам при проведении СЛР?',
                        'points'           => 2,
                        'order'            => 3,
                        'options' => [
                            ['text' => '15 нажатий : 1 вдох', 'is_correct' => false],
                            ['text' => '30 нажатий : 2 вдоха', 'is_correct' => true],
                            ['text' => '15 нажатий : 2 вдоха', 'is_correct' => false],
                            ['text' => '20 нажатий : 1 вдох',  'is_correct' => false],
                        ],
                    ],
                    [
                        'name'              => 'Номер скорой помощи',
                        'question_type_id'  => $text->id,
                        'question_text'     => 'Укажите номер телефона скорой медицинской помощи в России.',
                        'points'            => 1,
                        'order'             => 4,
                        'options'           => [],
                        'correct_answers'   => ['103', '112'],
                    ],
                ],
            ],
            [
                'test' => [
                    'name'          => 'Корпоративная культура и этика',
                    'slug'          => 'corporate-culture',
                    'description'   => 'Обучающий тест для новых сотрудников по корпоративным ценностям и кодексу делового поведения.',
                    'test_types_id' => $learningTest->id,
                    'start_date'    => now()->subDays(30),
                    'end_date'      => now()->addDays(60),
                    'attempt_limit' => 0, // 0 = без ограничений
                    'time_limit'    => null,
                    'is_active'     => true,
                ],
                'questions' => [
                    [
                        'name'             => 'Ценности компании',
                        'question_type_id' => $multiple->id,
                        'question_text'    => 'Выберите все корпоративные ценности нашей компании:',
                        'points'           => 2,
                        'order'            => 1,
                        'options' => [
                            ['text' => 'Честность',       'is_correct' => true],
                            ['text' => 'Безразличие',     'is_correct' => false],
                            ['text' => 'Ответственность', 'is_correct' => true],
                            ['text' => 'Уважение',        'is_correct' => true],
                            ['text' => 'Развитие',        'is_correct' => true],
                        ],
                    ],
                    [
                        'name'             => 'Недопустимое поведение',
                        'question_type_id' => $single->id,
                        'question_text'    => 'Какое из перечисленных поведений является недопустимым в компании?',
                        'points'           => 1,
                        'order'            => 2,
                        'options' => [
                            ['text' => 'Конструктивная критика работы коллеги', 'is_correct' => false],
                            ['text' => 'Дискриминация по полу или возрасту',   'is_correct' => true],
                            ['text' => 'Инициатива по улучшению процессов',    'is_correct' => false],
                            ['text' => 'Сообщение руководителю о проблеме',    'is_correct' => false],
                        ],
                    ],
                    [
                        'name'             => 'Разрешение конфликтов',
                        'question_type_id' => $single->id,
                        'question_text'    => 'Как следует поступить при возникновении рабочего конфликта с коллегой?',
                        'points'           => 1,
                        'order'            => 3,
                        'options' => [
                            ['text' => 'Игнорировать проблему',                         'is_correct' => false],
                            ['text' => 'Попытаться решить конструктивно, при необходимости привлечь HR', 'is_correct' => true],
                            ['text' => 'Жаловаться другим коллегам',                    'is_correct' => false],
                            ['text' => 'Немедленно эскалировать на уровень директора',  'is_correct' => false],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($testsData as $testData) {
            $test = Test::firstOrCreate(
                ['slug' => $testData['test']['slug']],
                $testData['test']
            );

            foreach ($testData['questions'] as $qData) {
                $options        = $qData['options'] ?? [];
                $correctAnswers = $qData['correct_answers'] ?? [];
                unset($qData['options'], $qData['correct_answers']);

                $question = Question::firstOrCreate(
                    ['test_id' => $test->id, 'name' => $qData['name']],
                    array_merge($qData, ['test_id' => $test->id])
                );

                // Варианты ответов (для одиночного и множественного выбора)
                foreach ($options as $optData) {
                    QuestionOption::firstOrCreate(
                        ['question_id' => $question->id, 'text' => $optData['text']],
                        array_merge($optData, ['question_id' => $question->id])
                    );
                }

                // Правильные текстовые ответы
                foreach ($correctAnswers as $answerText) {
                    QuestionCorrectAnswer::firstOrCreate(
                        ['question_id' => $question->id, 'text' => $answerText]
                    );
                }
            }
        }
    }
}
