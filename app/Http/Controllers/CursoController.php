<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index()
    {
       \Log::info('CursoController@index accessed');

    $cursos = Curso::orderBy('ano')->orderBy('nome')->get();

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
            'periodos' => 'required|integer|between:1,4'
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
            'periodos' => 'required|integer|between:1,4'
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
        // Atualiza os cursos: 1º ano vira 2º, 2º vira 3º, e remove os 3º anos
        Curso::where('ano', 3)->delete();
        Curso::where('ano', 2)->update(['ano' => 3]);
        Curso::where('ano', 1)->update(['ano' => 2]);

        return redirect()->route('admin.cursos.index')
            ->with('success', 'Ano letivo avançado com sucesso. Os cursos do 3º ano foram removidos.');
    }

    public function voltarAno()
    {
        // Voltar os anos: 2º ano vira 1º, 3º vira 2º
        Curso::where('ano', 2)->update(['ano' => 1]);
        Curso::where('ano', 3)->update(['ano' => 2]);

        // Nota: Não recriamos os cursos do 3º ano que foram excluídos no avanço
        // pois não temos como saber quais eram

        return redirect()->route('admin.cursos.index')
            ->with('success', 'Ano letivo retrocedido com sucesso. Nota: Os cursos do 3º ano excluídos anteriormente não foram restaurados.');
    }
}
