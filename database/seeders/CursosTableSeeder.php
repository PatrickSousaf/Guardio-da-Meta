<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Curso;

class CursosTableSeeder extends Seeder
{
    public function run()
    {
        $cursos = [
            ['nome' => 'Administração', 'ano' => 1, 'periodos' => 4],
            ['nome' => 'Desenvolvimento de Sistemas', 'ano' => 1, 'periodos' => 4],
            ['nome' => 'Edificações', 'ano' => 1, 'periodos' => 4],
            ['nome' => 'Informática', 'ano' => 1, 'periodos' => 4],
            ['nome' => 'Administração', 'ano' => 2, 'periodos' => 4],
            ['nome' => 'Edificações', 'ano' => 2, 'periodos' => 4],
            ['nome' => 'Informática', 'ano' => 2, 'periodos' => 4],
            ['nome' => 'Nutrição', 'ano' => 2, 'periodos' => 4],
            ['nome' => 'Agronegócio', 'ano' => 3, 'periodos' => 4],
            ['nome' => 'Edificações', 'ano' => 3, 'periodos' => 4],
            ['nome' => 'Informática', 'ano' => 3, 'periodos' => 4],
            ['nome' => 'Nutrição', 'ano' => 3, 'periodos' => 4],
        ];

        foreach ($cursos as $curso) {
            Curso::create($curso);
        }
    }
}
