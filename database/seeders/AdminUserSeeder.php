<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => 'password',
                'phone' => '081234567890',
                'address' => 'Kantor pusat',
                'role' => 'admin',
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'User Demo',
                'password' => 'password',
                'phone' => '081234567891',
                'address' => 'Alamat user demo',
                'role' => 'user',
                'status' => 'active',
            ]
        );
    }
}
