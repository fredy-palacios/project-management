<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'fredy',
            'email' => 'fredy@fredy.com',
            'password' => 'fredy123',
            'profile' => 'admin',
        ]);

        /*
        User::create([
            'name' => 'RUser',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'profile' => 'user',
        ]);

        User::factory()->count(10)->create();
        */
    }
}
