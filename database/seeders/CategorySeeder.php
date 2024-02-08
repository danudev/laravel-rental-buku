<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Category::truncate();
        Schema::enableForeignKeyConstraints();
        $data = [
            'comic', 'novel', 'fantasy', 'fiction', 'mystery', 'horror', 'romance', 'western', 'biography', 'history', 'science', 'science fiction', 'non-fiction', 'adventure', 'classic', 'young adult', 'children', 'young adult'
        ];

        foreach ($data as $value) {
            Category::insert(['name' => $value]);
        }
    }
}
