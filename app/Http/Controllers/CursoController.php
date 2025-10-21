<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\PeriodoDado;
use App\Models\MetaPeriodo;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CursoController extends Controller
{
    public function index()
    {
       \Log::info('CursoController@index accessed');

    $cursos = Curso::orderBy('ano')->orderBy('turma')->orderBy('nome')->get();

    // Debug: verifique os dados
    \Log::info('Cursos count: ' . $cursos->count());

    return view('admin.cursos.index', compact('cursos'));
    }

    public function create()
    {
        return view('admin.cursos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'ano' => 'required|integer|between:1,3',
            'periodos' => 'required|integer|between:1,4',
            'turma' => 'required|string|in:A,B,C,D'
        ]);

        Curso::create($request->all());

        return redirect()->route('admin.cursos.index')
            ->with('success', 'Curso criado com sucesso.');
    }

    public function edit(Curso $curso)
    {
        return view('admin.cursos.edit', compact('curso'));
    }

    public function update(Request $request, Curso $curso)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'ano' => 'required|integer|between:1,3',
            'periodos' => 'required|integer|between:1,4',
            'turma' => 'required|string|in:A,B,C,D'
        ]);

        $curso->update($request->all());

        return redirect()->route('admin.cursos.index')
            ->with('success', 'Curso atualizado com sucesso.');
    }

    public function destroy(Curso $curso)
    {
        $curso->delete();

        return redirect()->route('admin.cursos.index')
            ->with('success', 'Curso excluído com sucesso.');
    }

    public function avancarAno()
    {
        // Antes de avançar, gerar PDFs dos dados atuais para todos os cursos
        $this->gerarPdfsAntesAvanco();

        // Atualiza os cursos: 1º ano vira 2º, 2º vira 3º, e remove os 3º anos
        Curso::where('ano', 3)->delete();
        Curso::where('ano', 2)->update(['ano' => 3]);
        Curso::where('ano', 1)->update(['ano' => 2]);


        // Resetar dados dos períodos após avanço
        $this->resetarDadosPeriodos();

        return redirect()->route('admin.cursos.index')
            ->with('success', 'Ano letivo avançado com sucesso. PDFs salvos e dados resetados.');
    }



    private function gerarPdfsAntesAvanco()
    {
        $cursos = Curso::all();

        foreach ($cursos as $curso) {
            // Buscar dados reais dos períodos
            $dadosPeriodos = [];
            for ($i = 1; $i <= 4; $i++) {
                $dadosPeriodos[$i] = PeriodoDado::where('curso_id', $curso->id)
                    ->where('periodo', $i)
                    ->first();
            }

            // Buscar metas dos períodos
            $metas = [];
            for ($i = 1; $i <= 4; $i++) {
                $metas[$i] = MetaPeriodo::firstOrNew([
                    'curso_id' => $curso->id,
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

            // Gerar PDF
            $pdf = Pdf::loadView('periodos.comparativo-pdf', [
                'curso' => $curso,
                'ano' => $curso->ano,
                'periodoNumero' => 4, // Último período
                'periodo' => '4º Período',
                'dadosPeriodos' => $dadosPeriodos,
                'metas' => $metas
            ])->setPaper('a4', 'landscape');

            // Salvar PDF no storage
            $filename = 'comparativo_' . $curso->nome . '_ano_' . $curso->ano . '_final_' . date('Y-m-d_H-i-s') . '.pdf';
            Storage::put('pdfs/' . $filename, $pdf->output());
        }
    }

    private function resetarDadosPeriodos()
    {
        // Limpar todos os dados dos períodos
        PeriodoDado::truncate();

        // Opcional: resetar metas também se necessário
        // MetaPeriodo::truncate();
    }
}
