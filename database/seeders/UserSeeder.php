<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name'     => 'Admin User',
            'password' => bcrypt('password'),
        ]);

        User::firstOrCreate([
            'email' => 'manager@example.com',
        ], [
            'name'     => 'Manager User',
            'password' => bcrypt('password'),
        ]);

        User::firstOrCreate([
            'email' => 'user@example.com',
        ], [
            'name'     => 'Basic User',
            'password' => bcrypt('password'),
        ]);

    }
}
