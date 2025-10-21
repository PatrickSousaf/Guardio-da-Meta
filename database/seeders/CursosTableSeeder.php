<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Curso;

class CursosTableSeeder extends Seeder
{
    public function run()
    {
        $cursosPorAno = [
            1 => [
                'Administração',
                'Desenvolvimento de Sistemas',
                'Edificações',
                'Informática'
            ],
            2 => [
                'Administração',
                'Edificações',
                'Informática',
                'Nutrição'
            ],
            3 => [
                'Agronegócio',
                'Edificações',
                'Informática',
                'Nutrição'
            ]
        ];

        $turmas = ['A', 'B', 'C', 'D'];

        foreach ($cursosPorAno as $ano => $nomes) {
            foreach ($nomes as $index => $nome) {
                Curso::create([
                    'nome' => $nome,
                    'ano' => $ano,
                    'periodos' => 4,
                    'turma' => $turmas[$index]
                ]);
            }
        }
    }
}
