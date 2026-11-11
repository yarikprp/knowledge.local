<?php

namespace Database\Seeders;

use App\Models\Attempt;
use App\Models\Result;
use App\Models\User;
use App\Models\Test;
use Illuminate\Database\Seeder;

class ResultSeeder extends Seeder
{
    public function run(): void
    {
        // Формируем результаты на основе завершённых попыток
        $attempts = Attempt::whereNotNull('score')
            ->whereNotNull('finish_at')
            ->get();

        foreach ($attempts as $attempt) {
            $test        = Test::find($attempt->test_id);
            if (!$test) continue;

            $totalPoints = $test->questions()->sum('points');
            if ($totalPoints <= 0) continue;

            $percentage  = (int) round(($attempt->score / $totalPoints) * 100);
            $duration    = $attempt->started_at && $attempt->finish_at
                ? (int) ceil($attempt->started_at->diffInMinutes($attempt->finish_at))
                : null;

            $attemptedAt = $attempt->finish_at ?? $attempt->started_at ?? now();

            // Защита от нарушения unique constraint
            $exists = Result::where('user_id', $attempt->user_id)
                ->where('test_id', $attempt->test_id)
                ->where('attempted_at', $attemptedAt)
                ->exists();

            if (!$exists) {
                Result::create([
                    'user_id'        => $attempt->user_id,
                    'test_id'        => $attempt->test_id,
                    'score'          => $attempt->score,
                    'percentage'     => $percentage,
                    'passed'         => $attempt->is_passed,
                    'attempted_at'   => $attemptedAt,
                    'duration'       => $duration,
                ]);
            }
        }
    }
}
