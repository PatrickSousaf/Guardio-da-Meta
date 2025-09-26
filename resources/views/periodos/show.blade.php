@extends('adminlte::page')

@section('title', $curso->nome . ' - ' . $ano . ' - ' . $periodo)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-chart-line mr-2"></i>{{ $periodo }} - Visão Geral</h1>
        @if(Auth::user()->isManagement() || Auth::user()->isDirector())
        <div>
            <button onclick="toggleEditMode()" class="btn btn-success mr-2"><i class="fas fa-edit"></i> Editar</button>
            <button onclick="saveData()" class="btn btn-warning"><i class="fas fa-save"></i> Salvar Dados</button>
        </div>
        @endif
    </div>
@stop

@section('content')
    <!-- Notificação de Sucesso (inicialmente escondida) -->
    <div id="successNotification" class="notification-success">
        <div class="notification-content">
            <div class="notification-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="notification-text">
                <h4>✅ Dados Salvos com Sucesso!</h4>
                <p>As informações foram atualizadas no sistema.</p>
            </div>
            <button class="notification-close" onclick="hideNotification()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    @if(Auth::user()->isManagement() || Auth::user()->isDirector())
    <!-- Seção de Importação CSV -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-file-import mr-2"></i>Importar Dados do Período</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="csvFileInput" class="form-label">Selecionar arquivo CSV</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="csvFileInput" accept=".csv">
                                <label class="custom-file-label" for="csvFileInput">Escolher arquivo</label>
                            </div>
                            <div class="input-group-append">
                                <button class="btn btn-outline-info" type="button" id="processCsvBtn" onclick="processCSV()">
                                    <i class="fas fa-cog mr-1"></i> Processar
                                </button>
                            </div>
                        </div>
                        <small class="form-text text-muted">Selecione um arquivo CSV com os dados dos alunos</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Métricas em uma única tabela retangular -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-custom">
                    <h3 class="card-title"><i class="fas fa-table mr-2"></i>Métricas da Turma</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table table-bordered table-hover text-center mb-0">
                            <thead class="bg-lightblue">
                                <tr>
                                    <th colspan="4" class="bg-primary text-white">DADOS BÁSICOS</th>
                                    <th colspan="3" class="bg-info text-white">APROVAÇÕES</th>
                                    <th colspan="3" class="bg-success text-white">PERCENTUAIS</th>
                                    <th colspan="1" class="bg-warning text-white">IDE SALA</th>
                                </tr>
                                <tr>
                                    <!-- Dados Básicos -->
                                    <th class="bg-light">Total Alunos</th>
                                    <th class="bg-light">Média Geral</th>
                                    <th class="bg-light">Infrequência (%)</th>
                                    <th class="bg-light">Frequência</th>

                                    <!-- Aprovações -->
                                    <th class="bg-light">Acima Média PT</th>
                                    <th class="bg-light">Acima Média MAT</th>
                                    <th class="bg-light">Acima Média Geral</th>

                                    <!-- Percentuais -->
                                    <th class="bg-light">% Português</th>
                                    <th class="bg-light">% Matemática</th>
                                    <th class="bg-light">% Aprovação Geral</th>

                                    <!-- IDE Sala -->
                                    <th class="bg-light">Índice</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <!-- Dados Básicos -->
                                    <td>
                                        <input type="number" id="TotalAlunos" class="form-control text-center font-weight-bold metric-input"
                                               value="{{ $dados->total_alunos ?? 0 }}" oninput="calcularTudo()">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" id="mediaGeral" class="form-control text-center font-weight-bold metric-input"
                                               value="{{ number_format($dados->media_geral ?? 0, 2, '.', '') }}" oninput="calcularTudo()">
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" step="0.01" id="infrequencia" class="form-control text-center font-weight-bold metric-input"
                                                   value="{{ number_format($dados->infrequencia ?? 0, 2, '.', '') }}" oninput="calcularTudo()">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" id="frequencia" class="form-control text-center text-success font-weight-bold metric-display"
                                               value="{{ number_format($dados->frequencia ?? 0.85, 2, '.', '') }}" readonly>
                                    </td>

                                    <!-- Aprovações -->
                                    <td>
                                        <input type="number" id="acimaMediaPT" class="form-control text-center text-primary font-weight-bold metric-input"
                                               value="{{ $dados->acima_media_pt ?? 0 }}" oninput="calcularTudo()">
                                    </td>
                                    <td>
                                        <input type="number" id="acimaMediaMat" class="form-control text-center text-primary font-weight-bold metric-input"
                                               value="{{ $dados->acima_media_mat ?? 0 }}" oninput="calcularTudo()">
                                    </td>
                                    <td>
                                        <input type="number" id="acimaMediaGeral" class="form-control text-center text-primary font-weight-bold metric-input"
                                               value="{{ $dados->acima_media_geral ?? 0 }}" oninput="calcularTudo()">
                                    </td>

                                    <!-- Percentuais -->
                                    <td>
                                        <input type="number" step="0.01" id="percentualPT" class="form-control text-center text-info font-weight-bold metric-display"
                                               value="{{ number_format($dados->percentual_pt ?? 0, 2, '.', '') }}" readonly>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" id="percentualMat" class="form-control text-center text-info font-weight-bold metric-display"
                                               value="{{ number_format($dados->percentual_mat ?? 0, 2, '.', '') }}" readonly>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" id="percentualAprovacaoGeral" class="form-control text-center text-info font-weight-bold metric-display"
                                               value="{{ number_format($dados->percentual_aprovacao_geral ?? 0, 2, '.', '') }}" readonly>
                                    </td>

                                    <!-- IDE Sala -->
                                    <td>
                                        <input type="number" step="0.01" id="mediaTotal" class="form-control text-center font-weight-bold metric-display"
                                               value="{{ number_format($dados->media_total ?? 0, 2, '.', '') }}" readonly>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<link rel="stylesheet" href="{{ asset('css/periodo.css') }}">
<link rel="stylesheet" href="{{ asset('css/periodos-show.css') }}">
@stop

@section('js')
<script>
const routes = {
    salvarDados: '{{ route('admin.periodos.salvar-dados') }}',
    importarCsv: '{{ route("admin.periodos.importar-csv") }}'
};
const cursoId = {{ $curso->id }};
const periodo = {{ $periodoNumero }};
</script>
<script src="{{ asset('js/periodos-show.js') }}"></script>
<script>
initPeriodosShow(routes, cursoId, periodo);
</script>
@stop
