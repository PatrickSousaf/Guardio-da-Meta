<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\PeriodoDado;
use App\Models\MetaPeriodo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function comparativo($cursoId, $ano, $periodo)
    {
        $curso = Curso::findOrFail($cursoId);
        $periodoNumero = (int)$periodo;

        // Buscar dados reais dos períodos
        $dadosPeriodos = [];
        for ($i = 1; $i <= 4; $i++) {
            $dadosPeriodos[$i] = PeriodoDado::where('curso_id', $cursoId)
                ->where('periodo', $i)
                ->first();
        }

        // Buscar metas dos períodos - CORREÇÃO: valores padrão 0
        $metas = [];
        for ($i = 1; $i <= 4; $i++) {
            $metas[$i] = MetaPeriodo::firstOrNew([
                'curso_id' => $cursoId,
                'periodo' => $i
            ], [
                'turma' => $curso->nome,
                'alunos' => 0,
                'media_geral' => 0,
                'infrequencia' => 0,
                'frequencia' => 0,
                'aprovacao_lp' => 0,
                'aprovacao_mt' => 0,
                'aprovacao_geral' => 0,
                'total_aprovados' => 0,
                'percentual_pt' => 0,
                'percentual_mat' => 0,
                'percentual_geral' => 0,
                'ide_sala' => 0
            ]);
        }

        return view('periodos.comparativo', compact(
            'curso',
            'ano',
            'periodoNumero',
            'periodo',
            'dadosPeriodos',
            'metas'
        ));
    }

    public function salvarMetas(Request $request): JsonResponse
    {
        try {
            // Loga todo o request

            $cursoId = $request->curso_id ?? 0;
            $metasData = $request->metas ?? [];

            foreach ($metasData as $index => $metaData) {
                \Log::info("Meta #{$index} recebida:", $metaData);

                MetaPeriodo::updateOrCreate(
                    [
                        'curso_id' => $cursoId,
                        'periodo' => $metaData['periodo'] ?? 1
                    ],
                    [
                        'turma' => $metaData['turma'] ?? 'N/D',
                        'alunos' => $metaData['alunos'] ?? 0,
                        'media_geral' => $metaData['media_geral'] ?? 0,
                        'infrequencia' => $metaData['infrequencia'] ?? 0,
                        'frequencia' => $metaData['frequencia'] ?? 0,
                        'aprovacao_lp' => $metaData['aprovacao_lp'] ?? 0,
                        'aprovacao_mt' => $metaData['aprovacao_mt'] ?? 0,
                        'aprovacao_geral' => $metaData['aprovacao_geral'] ?? 0,
                        'total_aprovados' => $metaData['total_aprovados'] ?? 0,
                        'percentual_pt' => $metaData['percentual_pt'] ?? 0,
                        'percentual_mat' => $metaData['percentual_mat'] ?? 0,
                        'percentual_geral' => $metaData['percentual_geral'] ?? 0,
                        'ide_sala' => $metaData['ide_sala'] ?? 0
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Metas salvas com sucesso! (debug)'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro ao salvar metas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ], 500);
        }
    }

    public function gerarPdf($cursoId, $ano, $periodo)
    {
        $curso = Curso::findOrFail($cursoId);
        $periodoNumero = (int)$periodo;

        // Buscar dados reais dos períodos
        $dadosPeriodos = [];
        for ($i = 1; $i <= 4; $i++) {
            $dadosPeriodos[$i] = PeriodoDado::where('curso_id', $cursoId)
                ->where('periodo', $i)
                ->first();
        }

        // Buscar metas dos períodos
        $metas = [];
        for ($i = 1; $i <= 4; $i++) {
            $metas[$i] = MetaPeriodo::firstOrNew([
                'curso_id' => $cursoId,
                'periodo' => $i
            ], [
                'turma' => $curso->nome,
                'alunos' => 0,
                'media_geral' => 0,
                'infrequencia' => 0,
                'frequencia' => 0,
                'aprovacao_lp' => 0,
                'aprovacao_mt' => 0,
                'aprovacao_geral' => 0,
                'total_aprovados' => 0,
                'percentual_pt' => 0,
                'percentual_mat' => 0,
                'percentual_geral' => 0,
                'ide_sala' => 0
            ]);
        }

        $pdf = Pdf::loadView('periodos.comparativo-pdf', compact(
            'curso',
            'ano',
            'periodoNumero',
            'periodo',
            'dadosPeriodos',
            'metas'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('comparativo_' . $curso->nome . '_ano_' . $ano . '_periodo_' . $periodo . '.pdf');
    }

}

