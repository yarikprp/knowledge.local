<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Администратор'],
            ['name' => 'Ментор'],
            ['name' => 'Сотрудник'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']]);
        }
    }
}
