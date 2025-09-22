<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\PeriodoDado;
use Illuminate\Http\Request;

class PeriodoController extends Controller
{
    public function show($cursoId, $ano, $periodo)
    {
        $curso = Curso::findOrFail($cursoId);
        
        // Carrega os dados existentes para este período
        $dados = PeriodoDado::firstOrNew([
            'curso_id' => $curso->id,
            'periodo' => $periodo
        ]);

        return view('periodos.show', [
            'curso' => $curso,
            'periodoNumero' => $periodo,
            'periodo' => "{$periodo}º Período",
            'ano' => "{$ano}º Ano",
            'dados' => $dados
        ]);
    }

    public function storeData(Request $request)
    {
        $validated = $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'periodo' => 'required|integer',
            'total_alunos' => 'required|integer',
            'media_geral' => 'required|numeric',
            'infrequencia' => 'required|numeric',
            'frequencia' => 'required|numeric',
            'acima_media_pt' => 'required|integer',
            'acima_media_mat' => 'required|integer',
            'percentual_pt' => 'required|numeric',
            'percentual_mat' => 'required|numeric',
            'acima_media_geral' => 'required|integer',
            'percentual_aprovacao_geral' => 'required|numeric',
            'media_total' => 'required|numeric'
        ]);

        PeriodoDado::updateOrCreate(
            [
                'curso_id' => $validated['curso_id'],
                'periodo' => $validated['periodo']
            ],
            $validated
        );

        return response()->json([
            'success' => true,
            'message' => 'Dados salvos com sucesso!'
        ]);
    }
}