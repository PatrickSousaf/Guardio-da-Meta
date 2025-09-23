<?php
// database/seeders/DirectorUserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DirectorUserSeeder extends Seeder
{
    public function run()
    {
        // Cria o usuário diretor padrão
        User::create([
            'name' => 'Diretor Principal',
            'email' => 'diretor@escola.com',
            'password' => Hash::make('senha123'),
            'role' => 'director',
        ]);

        $this->command->info('Usuário diretor criado: diretor@escola.com / senha123');
    }
}
