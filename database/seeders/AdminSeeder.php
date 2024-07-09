<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'full_name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'phone' => '081311290187',
            'password' => bcrypt('123123'),
            'role' => 'admin'
        ]);
    }
}
