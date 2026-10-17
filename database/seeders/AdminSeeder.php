<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // password is hashed automatically by the model cast
        User::updateOrCreate(
            ['username' => 'admin'],
            ['name' => 'Administrator', 'password' => 'admin12345', 'role' => 'admin']
        );
    }
}
