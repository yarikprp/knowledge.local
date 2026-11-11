<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole    = Role::where('name', 'Администратор')->first();
        $mentorRole   = Role::where('name', 'Ментор')->first();
        $employeeRole = Role::where('name', 'Сотрудник')->first();

        // id=1 — Администратор (Gate::before даёт ему все права)
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'              => 'Администратор',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        // id=2 — Ментор
        $mentor = User::firstOrCreate(
            ['email' => 'mentor@example.com'],
            [
                'name'              => 'Иван Петров',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $mentor->roles()->syncWithoutDetaching([$mentorRole->id]);

        // id=3 — Сотрудник
        $employee = User::firstOrCreate(
            ['email' => 'employee@example.com'],
            [
                'name'              => 'Мария Сидорова',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $employee->roles()->syncWithoutDetaching([$employeeRole->id]);

        // Дополнительные сотрудники
        $extraUsers = [
            ['name' => 'Алексей Козлов',    'email' => 'kozlov@example.com'],
            ['name' => 'Елена Новикова',    'email' => 'novikova@example.com'],
            ['name' => 'Дмитрий Морозов',  'email' => 'morozov@example.com'],
            ['name' => 'Анна Волкова',      'email' => 'volkova@example.com'],
            ['name' => 'Сергей Лебедев',   'email' => 'lebedev@example.com'],
        ];

        foreach ($extraUsers as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name'              => $userData['name'],
                    'password'          => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $user->roles()->syncWithoutDetaching([$employeeRole->id]);
        }
    }
}
