@extends('adminlte::page')

@section('title', 'Editar Curso')

@section('content_header')
    <h1>Editar Curso</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.cursos.update', $curso->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="nome">Nome do Curso</label>
                    <input type="text" class="form-control" id="nome" name="nome" value="{{ $curso->nome }}" required>
                </div>
                <div class="form-group">
                    <label for="ano">Ano</label>
                    <select class="form-control" id="ano" name="ano" required>
                        <option value="1" {{ $curso->ano == 1 ? 'selected' : '' }}>1º Ano</option>
                        <option value="2" {{ $curso->ano == 2 ? 'selected' : '' }}>2º Ano</option>
                        <option value="3" {{ $curso->ano == 3 ? 'selected' : '' }}>3º Ano</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="periodos">Número de Períodos</label>
                    <select class="form-control" id="periodos" name="periodos" required>
                        <option value="4" {{ $curso->periodos == 4 ? 'selected' : '' }}>4 Períodos</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="turma">Turma</label>
                    <select class="form-control" id="turma" name="turma" required>
                        <option value="A" {{ $curso->turma == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ $curso->turma == 'B' ? 'selected' : '' }}>B</option>
                        <option value="C" {{ $curso->turma == 'C' ? 'selected' : '' }}>C</option>
                        <option value="D" {{ $curso->turma == 'D' ? 'selected' : '' }}>D</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Atualizar</button>
                <a href="{{ route('admin.cursos.index') }}" class="btn btn-default">Cancelar</a>
            </form>
        </div>
    </div>
@stop
