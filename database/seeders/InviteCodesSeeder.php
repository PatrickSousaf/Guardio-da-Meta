<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InviteCode;

class InviteCodesSeeder extends Seeder
{
    public function run()
    {
        // Códigos que o diretor pode usar para registrar outros usuários
        InviteCode::create([
            'code' => 'GESTOR2024',
            'role' => 'management',
            'max_uses' => 10,
            'expires_at' => now()->addYear(),
        ]);

        InviteCode::create([
            'code' => 'PROF2024',
            'role' => 'teacher',
            'max_uses' => 50,
            'expires_at' => now()->addYear(),
        ]);

        // Código especial para registrar novos diretores (uso único)
        InviteCode::create([
            'code' => 'DIRETOR2024',
            'role' => 'director',
            'max_uses' => 1,
            'expires_at' => now()->addYear(),
        ]);
    }
}
