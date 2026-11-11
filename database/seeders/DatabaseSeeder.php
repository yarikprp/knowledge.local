<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Удаляем старого тестового пользователя из исходного сидера, если он есть
        \App\Models\User::where('email', 'test@example.com')->delete();

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            NewsTypeSeeder::class,
            NewsSeeder::class,
            EventTypeSeeder::class,
            StatusSeeder::class,
            SubjectSeeder::class,
            MaterialSeeder::class,
            QuestionTypeSeeder::class,
            TestTypeSeeder::class,
            TestSeeder::class,
            CalendarSeeder::class,
            AttemptSeeder::class,
            ResultSeeder::class,
        ]);
    }
}
