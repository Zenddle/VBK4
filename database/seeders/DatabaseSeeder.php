<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([AdminSeeder::class, CategorySeeder::class]);

        foreach ([['2441919029', 'zikry']] as [$nim, $name]) {
            User::updateOrCreate(['nim' => $nim], ['name' => $name, 'password' => 'password', 'role' => 'student']);
        }

        $demo = [
            ['Fiksi', 'a Day In My Life', 2026],

        ];
        foreach ($demo as [$cat, $title, $year]) {
            Book::firstOrCreate(['title' => $title], [
                'category_id' => Category::where('name', $cat)->value('id'),
                'description' => 'Demo description for ' . $title . '.',
                'year' => $year, 'total_copies' => 2, 'available_copies' => 2,
            ]);
        }
    }
}