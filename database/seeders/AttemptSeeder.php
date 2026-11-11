<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Attempt;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Status;
use App\Models\Test;
use App\Models\User;
use Illuminate\Database\Seeder;

class AttemptSeeder extends Seeder
{
    public function run(): void
    {
        $statusDone       = Status::where('name', 'Завершён')->first();
        $statusInProgress = Status::where('name', 'В процессе')->first();

        $users = User::where('email', '!=', 'admin@example.com')->get();
        $tests = Test::with('questions.options')->get();

        foreach ($users as $user) {
            foreach ($tests->take(3) as $test) {
                $questions    = $test->questions;
                $totalPoints  = $questions->sum('points');
                $passingScore = (int) round($totalPoints * 0.8);
                $startedAt    = now()->subDays(rand(1, 10))->subHours(rand(1, 3));

                // Завершённая попытка
                $attempt = Attempt::firstOrCreate(
                    ['user_id' => $user->id, 'test_id' => $test->id, 'started_at' => $startedAt],
                    [
                        'user_id'         => $user->id,
                        'test_id'         => $test->id,
                        'total_questions' => $questions->count(),
                        'passing_score'   => $passingScore,
                        'started_at'      => $startedAt,
                        'finish_at'       => $startedAt->copy()->addMinutes(rand(5, $test->time_limit ?? 15)),
                        'score'           => null,
                        'is_passed'       => false,
                        'status_id'       => $statusDone->id,
                    ]
                );

                // Создаём ответы и считаем результат
                $totalScore = 0;
                foreach ($questions as $question) {
                    $isCorrect = (bool) rand(0, 1); // случайный результат
                    $score     = $isCorrect ? $question->points : 0;
                    $totalScore += $score;

                    // Выбираем опцию для ответа
                    $correctOption = $question->options->firstWhere('is_correct', true);
                    $wrongOption   = $question->options->firstWhere('is_correct', false);
                    $chosenOption  = $isCorrect ? $correctOption : $wrongOption;

                    Answer::firstOrCreate(
                        ['attempt_id' => $attempt->id, 'question_id' => $question->id],
                        [
                            'attempt_id'  => $attempt->id,
                            'question_id' => $question->id,
                            'name'        => $chosenOption?->text ?? 'Нет ответа',
                            'is_correct'  => $isCorrect,
                            'time_spent'  => rand(15, 120),
                        ]
                    );
                }

                // Обновляем итоговый балл
                $attempt->score     = $totalScore;
                $attempt->is_passed = $totalScore >= $passingScore;
                $attempt->save();
            }
        }

        // Незавершённая попытка для первого сотрудника на последнем тесте
        $employee  = User::where('email', 'employee@example.com')->first();
        $lastTest  = $tests->last();
        if ($employee && $lastTest) {
            $questions   = $lastTest->questions;
            $totalPoints = $questions->sum('points');

            Attempt::firstOrCreate(
                ['user_id' => $employee->id, 'test_id' => $lastTest->id, 'started_at' => now()->subMinutes(5)],
                [
                    'user_id'         => $employee->id,
                    'test_id'         => $lastTest->id,
                    'total_questions' => $questions->count(),
                    'passing_score'   => (int) round($totalPoints * 0.8),
                    'started_at'      => now()->subMinutes(5),
                    'finish_at'       => null,
                    'score'           => null,
                    'is_passed'       => false,
                    'status_id'       => $statusInProgress->id,
                ]
            );
        }
    }
}
