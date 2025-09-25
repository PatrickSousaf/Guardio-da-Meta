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
        <h3 class="card-title text-center w-100">Métrica das metas e resultados gerais: {{ $curso->nome }} </h3>
    </div>
    <div class="card-body p-2">
        <div class="table-responsive">
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
            <br>
            <div class="text-right">
                <button onclick="toggleEditMode()" class="btn btn-success mr-2"><i class="fas fa-edit"></i> Editar Metas</button>
                <button onclick="saveMetaData()" class="btn btn-success"><i class="fas fa-save"></i> Salvar Metas</button>
            </div>
            <br>
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
<style>
    .table th, .table td {
        padding: 0.35rem;
        font-size: 0.85rem;
        vertical-align: middle;
    }

    input[readonly] {
        background-color: #e6f7ff;
        cursor: not-allowed;
        font-weight: bold;
    }

    input.form-control-sm {
        height: 32px;
        text-align: center;
    }

    .table-active {
        background-color: #cce5ff !important;
    }

    /* Cores personalizáveis */
    :root {
        --bg-info: #17a2b8;          /* Azul */
        --bg-primary: #007bff;       /* Azul médio */
        --bg-success: #28a745;       /* Verde */
        --bg-warning: #ffc107;       /* Amarelo */
        --bg-secondary: #6c757d;     /* Cinza */

        --bg-default: #d1ecf1;       /* Cor padrão para campos de valor */

        --text-white: white;
        --text-dark: #212529;
        --table-active: #189a36;
    }

    /* Cores para os cabeçalhos */
    .bg-info { background-color: var(--bg-info) !important; color: var(--text-white); }
    .bg-primary { background-color: var(--bg-primary) !important; color: var(--text-white); }
    .bg-success { background-color: var(--bg-success) !important; color: var(--text-white); }
    .bg-warning { background-color: var(--bg-warning) !important; color: var(--text-dark); }
    .bg-secondary { background-color: var(--bg-secondary) !important; color: var(--text-white); }

    /* Cores para as células de dados */
    .bg-default { background-color: var(--bg-default) !important; }

    /* Cores para resultados */
    .bg-success { background-color: var(--bg-success) !important; color: var(--text-white) !important; }
    .bg-warning { background-color: var(--bg-warning) !important; color: var(--text-dark) !important; }
    .bg-secondary { background-color: var(--bg-secondary) !important; color: var(--text-white) !important; }

    .table-active {
        background-color: var(--table-active) !important;
    }

    input[readonly] {
        background-color: var(--bg-default);
        border: 1px solid #b6d7e0;
    }

    .table-responsive {
        box-shadow: 0 0 5px rgba(0,0,0,0.1);
        border-radius: 0.25rem;
    }

    /* Alinhamento dos cabeçalhos */
    .table thead th {
        vertical-align: middle;
        text-align: center;
    }

    /* Melhorar visualização das cores */
    .bg-success, .bg-warning, .bg-secondary {
        font-weight: bold;
    }

    .badge {
        min-width: 60px;
        text-align: center;
    }
</style>
@stop

@section('js')
<script>
let isEditMode = false;
const cursoId = {{ $curso->id }};
const saveUrl = "{{ route('admin.periodos.salvar-metas') }}";

function toggleEditMode() {
    isEditMode = !isEditMode;
    document.querySelectorAll('.meta-alunos, .meta-media, .meta-infrequencia, .meta-lp, .meta-mt, .meta-geral')
        .forEach(input => input.readOnly = !isEditMode);

    const btn = document.querySelector('button[onclick="toggleEditMode()"]');
    btn.classList.toggle('btn-success', !isEditMode);
    btn.classList.toggle('btn-danger', isEditMode);
    btn.innerHTML = isEditMode ?
        '<i class="fas fa-times mr-1"></i> Cancelar Edição' :
        '<i class="fas fa-edit mr-1"></i> Editar Metas';
}

function calcularMeta() {
    const periodo = this.getAttribute('data-periodo');
    const alunos = parseFloat(document.querySelector(`.meta-alunos[data-periodo="${periodo}"]`).value) || 0;
    const inf = parseFloat(document.querySelector(`.meta-infrequencia[data-periodo="${periodo}"]`).value) || 0;
    const lp = parseFloat(document.querySelector(`.meta-lp[data-periodo="${periodo}"]`).value) || 0;
    const mt = parseFloat(document.querySelector(`.meta-mt[data-periodo="${periodo}"]`).value) || 0;
    const geral = parseFloat(document.querySelector(`.meta-geral[data-periodo="${periodo}"]`).value) || 0;
    const media = parseFloat(document.querySelector(`.meta-media[data-periodo="${periodo}"]`).value) || 0;

    // Cálculos automáticos
    const freq = (100 - inf) / 100; // 0 a 1

    // PERCENTUAIS DEVEM SER 0-1, NÃO 0-100
    const percLP = alunos > 0 ? (lp / alunos) : 0; // Já entre 0-1
    const percMT = alunos > 0 ? (mt / alunos) : 0; // Já entre 0-1
    const percGeral = alunos > 0 ? (geral / alunos) : 0; // Já entre 0-1

    // IDE-SALA - Todos os valores entre 0-1
    const ide = (media * freq * percLP * percMT * percGeral * 10) / 10 ;

    // Atualizar campos calculados - AGORA EXIBINDO COMO 0-1
    document.querySelector(`.meta-frequencia[data-periodo="${periodo}"]`).value = freq.toFixed(2);
    document.querySelector(`.meta-percentual-pt[data-periodo="${periodo}"]`).value = percLP.toFixed(2); // 0-1
    document.querySelector(`.meta-percentual-mat[data-periodo="${periodo}"]`).value = percMT.toFixed(2); // 0-1
    document.querySelector(`.meta-percentual-geral[data-periodo="${periodo}"]`).value = percGeral.toFixed(2); // 0-1
    document.querySelector(`.meta-ide[data-periodo="${periodo}"]`).value = ide.toFixed(2);
}

async function saveMetaData() {
    if (!isEditMode) {
        alert('Ative o modo de edição primeiro!');
        return;
    }

    const now = new Date();
    const ano = now.getFullYear();
    const semestre = now.getMonth() < 6 ? 1 : 2;

    // Converter objeto para ARRAY - isso resolve 90% dos casos
    const metasArray = [];
    for (let i = 1; i <= 4; i++) {
        metasArray.push({
            periodo: i,
            alunos: parseInt(document.querySelector(`.meta-alunos[data-periodo="${i}"]`).value) || 0,
            media_geral: parseFloat(document.querySelector(`.meta-media[data-periodo="${i}"]`).value) || 0,
            infrequencia: parseFloat(document.querySelector(`.meta-infrequencia[data-periodo="${i}"]`).value) || 0,
            frequencia: parseFloat(document.querySelector(`.meta-frequencia[data-periodo="${i}"]`).value) || 0,
            aprovacao_lp: parseInt(document.querySelector(`.meta-lp[data-periodo="${i}"]`).value) || 0,
            aprovacao_mt: parseInt(document.querySelector(`.meta-mt[data-periodo="${i}"]`).value) || 0,
            aprovacao_geral: parseInt(document.querySelector(`.meta-geral[data-periodo="${i}"]`).value) || 0,
            percentual_pt: parseFloat(document.querySelector(`.meta-percentual-pt[data-periodo="${i}"]`).value) || 0,
            percentual_mat: parseFloat(document.querySelector(`.meta-percentual-mat[data-periodo="${i}"]`).value) || 0,
            percentual_geral: parseFloat(document.querySelector(`.meta-percentual-geral[data-periodo="${i}"]`).value) || 0,
            ide_sala: parseFloat(document.querySelector(`.meta-ide[data-periodo="${i}"]`).value) || 0
        });
    }

    try {
        const response = await fetch(saveUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                curso_id: cursoId,
                ano: ano,
                semestre: semestre,
                metas: metasArray // Agora é array, não objeto
            })
        });

        const result = await response.json();

        if (result.success) {
            alert('Metas salvas com sucesso!');
            toggleEditMode();
            location.reload();
        } else {
            alert(result.message || 'Erro ao salvar');
        }
    } catch (error) {
        alert('Erro: ' + error.message);
    }
}

// Inicialização
document.addEventListener('DOMContentLoaded', () => {
    // Adiciona event listeners para cálculos automáticos
    document.querySelectorAll('.meta-alunos, .meta-media, .meta-infrequencia, .meta-lp, .meta-mt, .meta-geral')
        .forEach(input => input.addEventListener('input', calcularMeta));

    // Calcula valores iniciais
    for (let i = 1; i <= 4; i++) {
        calcularMeta.call(document.querySelector(`.meta-alunos[data-periodo="${i}"]`));
    }
});
</script>
@stop
