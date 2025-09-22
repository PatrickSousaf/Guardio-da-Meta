<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Diretor',
                'email' => 'diretor@teste.com',
                'password' => Hash::make('123456'),
                'role' => 'director',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Professor',
                'email' => 'professor@teste.com',
                'password' => Hash::make('123456'),
                'role' => 'teacher',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Gestor',
                'email' => 'gestor@teste.com',
                'password' => Hash::make('123456'),
                'role' => 'management',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'anderson',
                'email' => 'anderson@teste.com',
                'password' => Hash::make('password'),
                'role' => 'director',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
