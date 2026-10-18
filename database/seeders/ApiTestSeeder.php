<?php
namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Mengembalikan data demo versi sebelumnya (Alice/Bob/Citra dan buku
 * "The Silent Garden" dkk) supaya bisa dipakai untuk pengujian Postman.
 * Aman dijalankan berulang kali: tidak menghapus apa pun, hanya menambah
 * data yang belum ada.
 *
 *   php artisan db:seed --class=ApiTestSeeder
 */
class ApiTestSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Fiction', 'Science', 'Technology', 'History', 'Business'] as $name) {
            Category::firstOrCreate(['name' => $name]);
        }

        foreach ([['2024001', 'Alice'], ['2024002', 'Bob'], ['2024003', 'Citra']] as [$nim, $name]) {
            User::firstOrCreate(['nim' => $nim], ['name' => $name, 'password' => 'password123', 'role' => 'student']);
        }

        // [kategori, judul, tahun, total, tersedia]
        // "Night Train" sengaja dibuat stok 0 untuk menguji BOOK_UNAVAILABLE.
        $books = [
            ['Fiction',    'The Silent Garden', 2015, 2, 2],
            ['Fiction',    'Night Train',       2019, 2, 0],
            ['Science',    'Cosmos Explained',  2012, 2, 2],
            ['Technology', 'Clean Code Basics', 2018, 2, 2],
            ['Technology', 'Learning Laravel',  2023, 2, 2],
            ['History',    'Ancient Empires',   2008, 2, 2],
            ['Business',   'Startup Playbook',  2020, 2, 2],
        ];

        foreach ($books as [$cat, $title, $year, $total, $available]) {
            Book::firstOrCreate(['title' => $title], [
                'category_id'      => Category::where('name', $cat)->value('id'),
                'description'      => 'Demo description for ' . $title . '.',
                'year'             => $year,
                'total_copies'     => $total,
                'available_copies' => $available,
            ]);
        }
    }
}
