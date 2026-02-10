<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $adminData = [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ];

        if (Schema::hasColumn('users', 'role')) {
            $adminData['role'] = 'admin';
        }

        User::factory()->create($adminData);

        if (Schema::hasColumn('users', 'role')) {
            User::factory(12)->create([
                'role' => 'buyer',
            ]);
        } else {
            User::factory(12)->create();
        }
    }
}
