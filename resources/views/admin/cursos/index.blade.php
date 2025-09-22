@extends('adminlte::page')

@section('title', 'Gerenciamento de Cursos')

@section('content_header')
    <h1>Gerenciamento dos Anos e Cursos</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i> Sucesso!</h5>
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Ano</th>
                        <th>Períodos</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cursos as $curso)
                    <tr>
                        <td>{{ $curso->nome }}</td>
                        <td>{{ $curso->ano }}º Ano</td>
                        <td>{{ $curso->periodos }}</td>
                        <td>
                            <a href="{{ route('admin.cursos.edit', $curso->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-pencil-alt"></i> Editar
                            </a>
                            <form action="{{ route('admin.cursos.destroy', $curso->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Tem certeza que deseja excluir este curso?')">
                                    <i class="fas fa-trash"></i> Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($cursos->isEmpty())
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-book-open fa-3x mb-3"></i>
                    <p>Nenhum curso cadastrado.</p>
                </div>
            @endif
        </div>
    </div>
    <div class="float-right">
        <a href="{{ route('admin.cursos.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Novo Curso
        </a>
        <form action="{{ route('admin.cursos.avancar-ano') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-primary"
                    onclick="return confirm('Tem certeza que deseja avançar o ano letivo? Os 3º anos serão EXCLUÍDOS e não poderão ser recuperados!')">
                <i class="fas fa-forward"></i> Avançar Ano
            </button>
        </form>
        <form action="{{ route('admin.cursos.voltar-ano') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-warning"
                    onclick="return confirm('Tem certeza que deseja voltar o ano letivo? Nota: Os cursos do 3º ano excluídos não serão restaurados.')">
                <i class="fas fa-backward"></i> Voltar Ano
            </button>
        </form>
    </div>
    <br><br><br>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@stop
