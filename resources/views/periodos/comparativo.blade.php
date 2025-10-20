@extends('adminlte::page')

@section('title', 'Comparativo Meta vs Resultado - ' . $curso->nome)

@section('content')
@php
    // Função para determinar a cor baseada na comparação (FORA DO LOOP)
    function getColorClass($valorResultado, $valorMeta, $isInverse = false) {
        if ($valorResultado === null || $valorMeta === null) {
            return 'bg-secondary text-white'; // Sem dados
        }

        if ($isInverse) {
            // Para infrequência (menor é melhor)
            return $valorResultado <= $valorMeta ? 'bg-success text-white' : 'bg-warning text-dark';
        } else {
            // Para outros valores (maior é melhor)
            return $valorResultado >= $valorMeta ? 'bg-success text-white' : 'bg-warning text-dark';
        }
    }
@endphp

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title text-center w-100">Metas e resultados gerais: {{ $curso->ano}}ª ano em {{ $curso->nome }}  </h3>
    </div>
    <div class="card-body p-2">
        <div class="table-responsive" style="overflow-x: auto;">
            <table class="table table-bordered table-hover text-center mb-0">
                <thead class="bg-success text-white">
                    <tr>
                        <th rowspan="2" class="bg-info">Período</th>
                        <th rowspan="2" class="bg-info">Tipo</th>
                        <th rowspan="2" class="bg-info">Alunos</th>
                        <th rowspan="2" class="bg-info">Média Geral</th>
                        <th colspan="2" class="bg-primary">Frequência</th>
                        <th colspan="6" class="bg-success">Aprovações</th>
                        <th rowspan="2" class="bg-warning">IDE-SALA</th>
                    </tr>
                    <tr>
                        <th class="bg-primary">Infrequência (%)</th>
                        <th class="bg-primary">Frequência</th>
                        <th class="bg-success-light">LP</th>
                        <th class="bg-success-light">% PT</th>
                        <th class="bg-success-light">MT</th>
                        <th class="bg-success-light">% MAT</th>
                        <th class="bg-success-light">Geral</th>
                        <th class="bg-success-light">% Geral</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 1; $i <= 4; $i++)
                    @php
                        $meta = $metas[$i];
                        $resultado = $dadosPeriodos[$i] ?? null;
                    @endphp
                    <tr class="{{ $i == $periodoNumero ? 'table-active' : '' }}">
                        <td rowspan="2" class="bg-info text-white">{{ $i }}º</td>
                        <td class="bg-info text-white font-weight-bold">Meta</td>
                        <td class="bg-default">
                            <input type="number" class="form-control form-control-sm meta-alunos"
                                   data-periodo="{{ $i }}" value="{{ $meta->alunos ?? 0 }}"
                                   min="0" readonly>
                        </td>
                        <td class="bg-default">
                            <input type="number" step="0.01" class="form-control form-control-sm meta-media"
                                   data-periodo="{{ $i }}" value="{{ $meta->media_geral ?? 0 }}"
                                   min="0" max="10" readonly>
                        </td>
                        <td class="bg-default">
                            <input type="number" step="0.01" class="form-control form-control-sm meta-infrequencia"
                                   data-periodo="{{ $i }}" value="{{ $meta->infrequencia ?? 0 }}"
                                   min="0" max="100" readonly>
                        </td>
                        <td class="bg-default">
                            <input type="number" step="0.01" class="form-control form-control-sm meta-frequencia"
                                   data-periodo="{{ $i }}" value="{{ number_format($meta->frequencia ?? 0, 2) }}"
                                   min="0" max="1" readonly>
                        </td>
                        <td class="bg-default">
                            <input type="number" class="form-control form-control-sm meta-lp"
                                   data-periodo="{{ $i }}" value="{{ $meta->aprovacao_lp ?? 0 }}"
                                   min="0" readonly>
                        </td>
                        <td class="bg-default">
                            <input type="number" step="0.01" class="form-control form-control-sm meta-percentual-pt"
                                   data-periodo="{{ $i }}" value="{{ number_format($meta->percentual_pt ?? 0, 2) }}"
                                   readonly>
                        </td>
                        <td class="bg-default">
                            <input type="number" class="form-control form-control-sm meta-mt"
                                   data-periodo="{{ $i }}" value="{{ $meta->aprovacao_mt ?? 0 }}"
                                   min="0" readonly>
                        </td>
                        <td class="bg-default">
                            <input type="number" step="0.01" class="form-control form-control-sm meta-percentual-mat"
                                   data-periodo="{{ $i }}" value="{{ number_format($meta->percentual_mat ?? 0, 2) }}"
                                   readonly>
                        </td>
                        <td class="bg-default">
                            <input type="number" class="form-control form-control-sm meta-geral"
                                   data-periodo="{{ $i }}" value="{{ $meta->aprovacao_geral ?? 0 }}"
                                   min="0" readonly>
                        </td>
                        <td class="bg-default">
                            <input type="number" step="0.01" class="form-control form-control-sm meta-percentual-geral"
                                   data-periodo="{{ $i }}" value="{{ number_format($meta->percentual_geral ?? 0, 2) }}"
                                   readonly>
                        </td>
                        <td class="bg-default">
                            <input type="number" step="0.01" class="form-control form-control-sm meta-ide"
                                   data-periodo="{{ $i }}" value="{{ number_format($meta->ide_sala ?? 0, 2) }}"
                                   readonly>
                        </td>
                    </tr>
                    <tr class="{{ $i == $periodoNumero ? 'table-active' : '' }}">
                        <td class="bg-info text-white font-weight-bold">Resultado</td>

                        {{-- Alunos --}}
                        <td class="{{ getColorClass($resultado->total_alunos ?? null, $meta->alunos ?? null) }}">
                            {{ $resultado->total_alunos ?? 'N/D' }}
                        </td>

                        {{-- Média Geral --}}
                        <td class="{{ getColorClass($resultado->media_geral ?? null, $meta->media_geral ?? null) }}">
                            {{ $resultado ? number_format($resultado->media_geral, 2) : 'N/D' }}
                        </td>

                        {{-- Infrequência (inverso: menor é melhor) --}}
                        <td class="{{ getColorClass($resultado->infrequencia ?? null, $meta->infrequencia ?? null, true) }}">
                            {{ $resultado ? number_format($resultado->infrequencia, 2) : 'N/D' }}
                        </td>

                        {{-- Frequência --}}
                        <td class="{{ getColorClass($resultado->frequencia ?? null, $meta->frequencia ?? null) }}">
                            {{ $resultado ? number_format($resultado->frequencia, 2) : 'N/D' }}
                        </td>

                        {{-- Aprovação LP --}}
                        <td class="{{ getColorClass($resultado->acima_media_pt ?? null, $meta->aprovacao_lp ?? null) }}">
                            {{ $resultado->acima_media_pt ?? 'N/D' }}
                        </td>

                        {{-- % PT --}}
                        <td class="{{ getColorClass($resultado->percentual_pt ?? null, $meta->percentual_pt ?? null) }}">
                            {{ $resultado ? number_format($resultado->percentual_pt, 2) : 'N/D' }}
                        </td>

                        {{-- Aprovação MT --}}
                        <td class="{{ getColorClass($resultado->acima_media_mat ?? null, $meta->aprovacao_mt ?? null) }}">
                            {{ $resultado->acima_media_mat ?? 'N/D' }}
                        </td>

                        {{-- % MAT --}}
                        <td class="{{ getColorClass($resultado->percentual_mat ?? null, $meta->percentual_mat ?? null) }}">
                            {{ $resultado ? number_format($resultado->percentual_mat, 2) : 'N/D' }}
                        </td>

                        {{-- Aprovação Geral --}}
                        <td class="{{ getColorClass($resultado->acima_media_geral ?? null, $meta->aprovacao_geral ?? null) }}">
                            {{ $resultado->acima_media_geral ?? 'N/D' }}
                        </td>

                        {{-- % Geral --}}
                        <td class="{{ getColorClass($resultado->percentual_aprovacao_geral ?? null, $meta->percentual_geral ?? null) }}">
                            {{ $resultado ? number_format($resultado->percentual_aprovacao_geral, 2) : 'N/D' }}
                        </td>

                        {{-- IDE-SALA --}}
                        <td class="{{ getColorClass($resultado->media_total ?? null, $meta->ide_sala ?? null) }}">
                            {{ $resultado ? number_format($resultado->media_total, 2) : 'N/D' }}
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
            @if(Auth::user()->isManagement() || Auth::user()->isDirector())
            <br>
            <div class="text-right">
                <a href="{{ route('admin.periodos.gerar-pdf', ['curso' => $curso->id, 'ano' => $ano, 'periodo' => $periodo]) }}" class="btn btn-primary mr-2" target="_blank">
                    <i class="fas fa-file-pdf"></i> Gerar PDF
                </a>
                <button onclick="toggleEditMode()" class="btn btn-success mr-2"><i class="fas fa-edit"></i> Editar Metas</button>
                <button onclick="saveMetaData()" class="btn btn-success"><i class="fas fa-save"></i> Salvar Metas</button>
            </div>
            <br>
            @endif
        </div>
    </div>
</div>

{{-- Legenda --}}
<div class="card mt-3">
    <div class="card-header bg-light">
        <h5 class="card-title mb-0">Legenda das Cores</h5>
    </div>
    <div class="card-body">
        <div class="d-flex flex-wrap align-items-center" style="gap: 200px;">
            <div class="d-flex align-items-center" style="gap: 10px;">
                <span class="badge bg-success p-2">Verde</span>
                <span>Resultado atingiu ou superou a meta</span>
            </div>
            <div class="d-flex align-items-center" style="gap: 10px;">
                <span class="badge bg-warning p-2">Amarelo</span>
                <span>Resultado abaixo da meta</span>
            </div>
            <div class="d-flex align-items-center" style="gap: 10px;">
                <span class="badge bg-secondary p-2">Cinza</span>
                <span>Dados não criados ainda</span>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="{{ asset('css/periodos-comparativo.css') }}">
@stop

@section('js')
<script>
const cursoId = {{ $curso->id }};
const saveUrl = "{{ route('admin.periodos.salvar-metas') }}";
</script>
<script src="{{ asset('js/periodos-comparativo.js') }}"></script>
<script>
initPeriodosComparativo(cursoId, saveUrl);
</script>
@stop
