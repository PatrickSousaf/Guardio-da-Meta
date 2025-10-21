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
            <h3 class="card-title">Histórico de PDFs por Ano</h3>
        </div>
        <div class="card-body">
            @if(empty($pdfs))
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-file-pdf fa-3x mb-3"></i>
                    <p>Nenhum PDF salvo ainda.</p>
                    <small>Os PDFs são gerados automaticamente quando o ano letivo é avançado.</small>
                </div>
            @else
                @php
                    $pdfsGrouped = collect($pdfs)->groupBy('ano');
                @endphp

                <div class="row">
                    @foreach($pdfsGrouped as $ano => $pdfsDoAno)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 border-primary">
                                <div class="card-body text-center">
                                    <i class="fas fa-calendar-alt fa-3x text-primary mb-3"></i>
                                    <h5 class="card-title">{{ $ano }}º Ano</h5>
                                    <p class="card-text">{{ count($pdfsDoAno) }} PDF(s) salvo(s)</p>
                                    <a href="{{ route('admin.pdfs.ano', $ano) }}" class="btn btn-primary">
                                        <i class="fas fa-eye"></i> Ver Histórico
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@stop
