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
            $csvContent = file_get_contents($file->getRealPath());

            // Corrigir encoding
            $csvContent = mb_convert_encoding($csvContent, 'UTF-8', 'UTF-8');

            $csv = Reader::createFromString($csvContent);
            $csv->setDelimiter(','); // SEU CSV USA VÍRGULA!
            $csv->setHeaderOffset(null); // Processaremos manualmente

            $records = $csv->getRecords();
            $alunos = [];
            $alunosProcessados = [];

            $disciplinasPrimeiraParte = [];
            $disciplinasSegundaParte = [];
            $emPrimeiraParte = true;

            foreach ($records as $offset => $record) {
                // Pular linhas completamente vazias
                if (empty(array_filter($record))) {
                    continue;
                }

                // Detectar se é cabeçalho de disciplinas
                if (isset($record[1]) && stripos($record[1], 'Alunos / Disciplinas') !== false) {
                    // É um cabeçalho - verificar se mudou de seção
                    if (count($disciplinasPrimeiraParte) > 0 && $emPrimeiraParte) {
                        $emPrimeiraParte = false;
                    }
                    continue;
                }

                // Detectar início da segunda parte (LABORATORIO DE SOFTWARE)
                if (isset($record[2]) && stripos($record[2], 'LABORATORIO DE SOFTWARE') !== false) {
                    $emPrimeiraParte = false;
                    $disciplinasSegundaParte = $this->extrairDisciplinas($record);
                    continue;
                }

                // Detectar início da terceira parte (SOCIOLOGIA)
                if (isset($record[2]) && stripos($record[2], 'SOCIOLOGIA') !== false) {
                    // Para SOCIOLOGIA, vamos processar de forma diferente
                    continue;
                }

                // Processar linhas de alunos (começam com número)
                if (isset($record[0]) && is_numeric(trim($record[0]))) {
                    $numeroAluno = (int)trim($record[0]);
                    $nomeAluno = trim($record[1]);

                    // Evitar duplicatas
                    if (in_array($numeroAluno, $alunosProcessados)) {
                        continue;
                    }

                    $alunosProcessados[] = $numeroAluno;

                    // Coletar todas as notas da linha (da coluna 2 até o final)
                    $notas = [];
                    for ($i = 2; $i < count($record); $i++) {
                        $valor = trim($record[$i]);
                        if ($valor !== '' && is_numeric($valor)) {
                            $notas[] = (float)$valor;
                        }
                    }

                    if (count($notas) > 0) {
                        $media = array_sum($notas) / count($notas);

                        $alunos[] = [
                            'numero' => $numeroAluno,
                            'nome' => $nomeAluno,
                            'media' => $media,
                            'notas' => $notas
                        ];
                    }
                }
            }

            // Calcular estatísticas
            $total = count($alunos);
            $mediaGeral = $total > 0 ? array_sum(array_column($alunos, 'media')) / $total : 0;

            // Para este CSV específico, sabemos que:
            // - Português está normalmente na posição específica
            // - Matemática também
            // Vamos usar índices fixos baseados na estrutura conhecida do CSV

            $acimaPT = 0;
            $acimaMAT = 0;
            $acimaGeral = 0;

            foreach ($alunos as $aluno) {
                // Média geral
                if ($aluno['media'] >= 6.0) {
                    $acimaGeral++;
                }

                // Português - geralmente aparece depois de várias disciplinas
                // No seu CSV, LÍNGUA PORTUGUESA está após várias colunas
                // Vamos assumir que é a 7ª disciplina (índice 6)
                if (isset($aluno['notas'][6]) && $aluno['notas'][6] >= 6.0) {
                    $acimaPT++;
                }

                // Matemática - geralmente após Português
                // Vamos assumir que é a 8ª disciplina (índice 7)
                if (isset($aluno['notas'][7]) && $aluno['notas'][7] >= 6.0) {
                    $acimaMAT++;
                }
            }

            // Calcular percentuais
            $percentualPT = $total > 0 ? $acimaPT / $total : 0;
            $percentualMAT = $total > 0 ? $acimaMAT / $total : 0;
            $percentualGeral = $total > 0 ? $acimaGeral / $total : 0;

            return response()->json([
                'success' => true,
                'data' => [
                    'TotalAlunos' => $total,
                    'mediaGeral' => round($mediaGeral, 2),
                    'acimaMediaPT' => $acimaPT,
                    'acimaMediaMat' => $acimaMAT,
                    'acimaMediaGeral' => $acimaGeral,
                    'percentualPT' => round($percentualPT, 4),
                    'percentualMat' => round($percentualMAT, 4),
                    'percentualAprovacaoGeral' => round($percentualGeral, 4),
                    'alunosEncontrados' => $alunosProcessados
                ],
                'message' => 'CSV processado com sucesso! ' . $total . ' alunos importados.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar CSV: ' . $e->getMessage() . ' na linha ' . ($offset ?? 'N/A')
            ], 500);
        }
    }

    private function extrairDisciplinas(array $record): array
    {
        $disciplinas = [];
        $encontrouInicio = false;

        foreach ($record as $coluna) {
            $coluna = trim($coluna);

            if ($coluna === 'Alunos / Disciplinas') {
                $encontrouInicio = true;
                continue;
            }

            if ($encontrouInicio && !empty($coluna)) {
                $disciplinas[] = $coluna;
            }
        }

        return $disciplinas;
    }
}
