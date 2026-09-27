<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Customer Satu',
                'password' => Hash::make('user12345'),
            ]
        );
    }
}