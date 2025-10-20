@extends('adminlte::page')

@section('title', 'Histórico de PDFs')


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
            <h3 class="card-title">Histórico de PDFs Salvos</h3>
        </div>
        <div class="card-body">
            @if(empty($pdfs))
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-file-pdf fa-3x mb-3"></i>
                    <p>Nenhum PDF salvo ainda.</p>
                    <small>Os PDFs são gerados automaticamente quando o ano letivo é avançado.</small>
                </div>
            @else
                <div class="mb-3">
                    <form action="{{ route('admin.pdfs.deleteAll') }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir TODOS os PDFs? Esta ação não pode ser desfeita.')">
                            <i class="fas fa-trash"></i> Excluir Todos os PDFs
                        </button>
                    </form>
                </div>

                @php
                    $pdfsGrouped = collect($pdfs)->groupBy('ano');
                @endphp

                @php
                    $pdfsGrouped = collect($pdfs)->groupBy('ano');
                @endphp

                @foreach($pdfsGrouped as $ano => $pdfsDoAno)
                    <h4 class="mt-4 mb-3">{{ $ano }}º Ano</h4>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Curso</th>
                                    <th>Tamanho</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pdfsDoAno as $pdf)
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
                @endforeach
            @endif
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@stop
