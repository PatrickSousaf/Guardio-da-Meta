<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
    public function index()
    {
        // Listar todos os PDFs salvos
        $pdfs = [];

        if (Storage::exists('pdfs')) {
            $files = Storage::files('pdfs');

            foreach ($files as $file) {
                $filename = basename($file);
                $path = storage_path('app/' . $file);

                if (preg_match('/comparativo_(.+)_ano_(\d+)_final_(.+)\.pdf/', $filename, $matches)) {
                    $cursoNome = $matches[1];
                    $ano = $matches[2];
                    $data = $matches[3];

                    $pdfs[] = [
                        'filename' => $filename,
                        'curso_nome' => str_replace('_', ' ', $cursoNome),
                        'ano' => $ano,
                        'data' => $data,
                        'size' => Storage::size($file),
                        'url' => route('admin.pdfs.download', $filename)
                    ];
                }
            }

            // Ordenar por data decrescente (mais recentes primeiro)
            usort($pdfs, function($a, $b) {
                return strtotime($b['data']) <=> strtotime($a['data']);
            });
        }

        return view('admin.pdfs.index', compact('pdfs'));
    }

    public function download($filename)
    {
        $path = 'pdfs/' . $filename;

        if (!Storage::exists($path)) {
            abort(404, 'Arquivo não encontrado.');
        }

        return Storage::download($path);
    }

    public function delete($filename)
    {
        $path = 'pdfs/' . $filename;

        if (!Storage::exists($path)) {
            return redirect()->route('admin.pdfs.index')
                ->with('error', 'Arquivo não encontrado.');
        }

        Storage::delete($path);

        return redirect()->route('admin.pdfs.index')
            ->with('success', 'PDF excluído com sucesso.');
    }

    public function showAno($ano)
    {
        // Listar PDFs de um ano específico
        $pdfs = [];

        if (Storage::exists('pdfs')) {
            $files = Storage::files('pdfs');

            foreach ($files as $file) {
                $filename = basename($file);
                $path = storage_path('app/' . $file);

                if (preg_match('/comparativo_(.+)_ano_(\d+)_final_(.+)\.pdf/', $filename, $matches)) {
                    $cursoNome = $matches[1];
                    $anoPdf = $matches[2];
                    $data = $matches[3];

                    if ($anoPdf == $ano) {
                        $pdfs[] = [
                            'filename' => $filename,
                            'curso_nome' => str_replace('_', ' ', $cursoNome),
                            'ano' => $ano,
                            'data' => $data,
                            'size' => Storage::size($file),
                            'url' => route('admin.pdfs.download', $filename)
                        ];
                    }
                }
            }

            // Ordenar por data decrescente (mais recentes primeiro)
            usort($pdfs, function($a, $b) {
                return strtotime($b['data']) <=> strtotime($a['data']);
            });
        }

        return view('admin.pdfs.ano', compact('pdfs', 'ano'));
    }

    public function deleteAllAno($ano)
    {
        $files = Storage::files('pdfs');

        if (empty($files)) {
            return redirect()->route('admin.pdfs.ano', $ano)
                ->with('info', 'Nenhum PDF para excluir.');
        }

        $deletedCount = 0;
        foreach ($files as $file) {
            $filename = basename($file);

            if (preg_match('/comparativo_(.+)_ano_(\d+)_final_(.+)\.pdf/', $filename, $matches)) {
                $anoPdf = $matches[2];

                if ($anoPdf == $ano) {
                    Storage::delete($file);
                    $deletedCount++;
                }
            }
        }

        return redirect()->route('admin.pdfs.ano', $ano)
            ->with('success', "{$deletedCount} PDF(s) do {$ano}º ano excluído(s) com sucesso.");
    }

    public function deleteAll()
    {
        $files = Storage::files('pdfs');

        if (empty($files)) {
            return redirect()->route('admin.pdfs.index')
                ->with('info', 'Nenhum PDF para excluir.');
        }

        $deletedCount = 0;
        foreach ($files as $file) {
            Storage::delete($file);
            $deletedCount++;
        }

        return redirect()->route('admin.pdfs.index')
            ->with('success', "{$deletedCount} PDF(s) excluído(s) com sucesso.");
    }
}
