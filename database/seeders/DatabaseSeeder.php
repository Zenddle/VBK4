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

        foreach ([['2024001', 'Alice'], ['2024002', 'Bob'], ['2024003', 'Citra']] as [$nim, $name]) {
            User::updateOrCreate(['nim' => $nim], ['name' => $name, 'password' => 'password123', 'role' => 'student']);
        }

        $demo = [
            ['Fiction', 'The Silent Garden', 2015], ['Fiction', 'Night Train', 2019],
            ['Science', 'Cosmos Explained', 2012], ['Technology', 'Clean Code Basics', 2018],
            ['Technology', 'Learning Laravel', 2023], ['History', 'Ancient Empires', 2008],
            ['Business', 'Startup Playbook', 2020],
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