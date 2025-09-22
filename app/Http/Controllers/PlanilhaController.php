<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\Csv\Reader;

class PlanilhaController extends Controller
{
    public function importar(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
            'curso_id' => 'required|exists:cursos,id',
            'periodo' => 'required|integer'
        ]);

        try {
            $file = $request->file('file');
            $csv = Reader::createFromPath($file->getRealPath(), 'r');
            $csv->setDelimiter(';');
            $csv->setHeaderOffset(2); // Pular as duas primeiras linhas (cabeçalhos)

            $records = $csv->getRecords();
            $alunos = [];

            foreach ($records as $record) {
                if (empty($record['Alunos / Disciplinas']) || empty($record[0])) continue;

                // Extrair notas numéricas
                $notas = [];
                foreach ($record as $key => $value) {
                    if ($key !== 'Alunos / Disciplinas' && $key !== '' && is_numeric($value)) {
                        $notas[] = (float) $value;
                    }
                }

                if (count($notas) === 0) continue;

                $media = array_sum($notas) / count($notas);

                $alunos[] = [
                    'nome' => $record['Alunos / Disciplinas'],
                    'media' => $media,
                    'notas' => $notas
                ];
            }

            // Calcular estatísticas
            $total = count($alunos);
            $mediaGeral = $total > 0 ? array_sum(array_column($alunos, 'media')) / $total : 0;

            // Contar alunos acima da média em cada disciplina (assumindo que as 2 primeiras colunas são PT e MAT)
            $acimaPT = 0;
            $acimaMAT = 0;
            $acimaGeral = 0;

            foreach ($alunos as $aluno) {
                // Verificar se está acima da média geral (6.0)
                if ($aluno['media'] >= 6.0) {
                    $acimaGeral++;
                }

                // Verificar notas específicas (assumindo que as duas primeiras notas são PT e MAT)
                if (isset($aluno['notas'][0]) && $aluno['notas'][0] >= 6.0) {
                    $acimaPT++;
                }
                if (isset($aluno['notas'][1]) && $aluno['notas'][1] >= 6.0) {
                    $acimaMAT++;
                }
            }

            // Calcular percentuais (0 a 1)
            $percentualPT = $total > 0 ? $acimaPT / $total : 0;
            $percentualMAT = $total > 0 ? $acimaMAT / $total : 0;
            $percentualGeral = $total > 0 ? $acimaGeral / $total : 0;

            return response()->json([
                'success' => true,
                'data' => [
                    'TotalAlunos' => $total,
                    'mediaGeral' => round($mediaGeral, 1),
                    'acimaMediaPT' => $acimaPT,
                    'acimaMediaMat' => $acimaMAT,
                    'acimaMediaGeral' => $acimaGeral,
                    'percentualPT' => round($percentualPT, 4), // 0 a 1
                    'percentualMat' => round($percentualMAT, 4), // 0 a 1
                    'percentualAprovacaoGeral' => round($percentualGeral, 4), // 0 a 1
                ],
                'message' => 'CSV processado com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar CSV: ' . $e->getMessage()
            ], 500);
        }
    }
}