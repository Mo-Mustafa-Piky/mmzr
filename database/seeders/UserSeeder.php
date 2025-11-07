<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);

        $users = [
            ['name' => 'Sabah', 'email' => 'sabah@pikyhost.com'],
            ['name' => 'A. Labeb', 'email' => 'a.labeb@pikyhost.com'],
            ['name' => 'Mo Mostafa', 'email' => 'mo.mostafa@pikyhost.com'],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => bcrypt('password'),
                ]
            );
            if (!$user->hasRole('super_admin')) {
                $user->assignRole('super_admin');
            }
        }
    }
}
