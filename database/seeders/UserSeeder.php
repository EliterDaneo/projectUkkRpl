<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'ayu',
                'email'=> 'ayu@gmail.com',
                'password'=> bcrypt('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'nala',
                'email'=> 'nala@gmail.com',
                'password'=> bcrypt('password'),
                'role' => 'user',
            ]
        ];

    }
}
