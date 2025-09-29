<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = [
            'name' => 'Arifin',
            'email' => 'arifin@gmail.com',
            'password' => bcrypt('password'),
            // 'role' => 'admin',
        ];

        User::create($user);
    }
}
