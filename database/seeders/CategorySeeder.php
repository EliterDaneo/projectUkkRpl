<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [];

        for ($i = 1; $i <= 100; $i++) {
            $categories[] = [
                'name' => 'Kategori ' . $i,
            ];
        }

        Category::insert($categories);
    }
}
