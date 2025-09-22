@extends('adminlte::page')

@section('title', 'Adicionar Novo Curso')

@section('content_header')
    <h1>Adicionar Novo Curso</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.cursos.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="nome">Nome do Curso</label>
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Ex: Administração" required>
                </div>
                <div class="form-group">
                    <label for="ano">Ano</label>
                    <select class="form-control" id="ano" name="ano" required>
                        <option value="1">1º Ano</option>
                        <option value="2">2º Ano</option>
                        <option value="3">3º Ano</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="periodos">Número de Períodos</label>
                    <select class="form-control" id="periodos" name="periodos" required>
                        <option value="1">1 Período</option>
                        <option value="2">2 Períodos</option>
                        <option value="3">3 Períodos</option>
                        <option value="4">4 Períodos</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('admin.cursos.index') }}" class="btn btn-default">Cancelar</a>
            </form>
        </div>
    </div>
@stop
