<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'jonasclocin01@gmail.com'],
            [
                'name' => 'Ventoy',
                'password' => Hash::make('12345678'),
                'role' => 'admin'
            ]
        );
    }
}
