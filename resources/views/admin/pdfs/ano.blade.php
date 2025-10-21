@extends('adminlte::page')

@section('title', "Histórico de PDFs - {$ano}º Ano")

@section('content')
<br>
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i> Sucesso!</h5>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-exclamation-triangle"></i> Erro!</h5>
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Histórico de PDFs Salvos - {{ $ano }}º Ano</h3>
            <div class="card-tools">
                <a href="{{ route('admin.pdfs.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Voltar ao Histórico Geral
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(empty($pdfs))
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-file-pdf fa-3x mb-3"></i>
                    <p>Nenhum PDF salvo para o {{ $ano }}º ano.</p>
                    <small>Os PDFs são gerados automaticamente quando o ano letivo é avançado.</small>
                </div>
            @else
                <div class="mb-3">
                    <form action="{{ route('admin.pdfs.deleteAllAno', $ano) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir TODOS os PDFs do {{ $ano }}º ano? Esta ação não pode ser desfeita.')">
                            <i class="fas fa-trash"></i> Excluir Todos os PDFs do {{ $ano }}º Ano
                        </button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Curso</th>
                                <th>Tamanho</th>

                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pdfs as $pdf)
                            <tr>
                                <td>{{ $pdf['curso_nome'] }}</td>
                                <td>{{ number_format($pdf['size'] / 1024, 2) }} KB</td>
                                <td>
                                    <a href="{{ $pdf['url'] }}" class="btn btn-info btn-sm" target="_blank">
                                        <i class="fas fa-download"></i> Baixar
                                    </a>
                                    <form action="{{ route('admin.pdfs.delete', $pdf['filename']) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Tem certeza que deseja excluir este PDF?')">
                                            <i class="fas fa-trash"></i> Excluir
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@stop
