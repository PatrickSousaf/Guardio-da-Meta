@extends('adminlte::page')

@section('title', 'Comparativo Meta vs Resultado - ' . $curso->nome)

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-chart-line mr-2"></i>Comparativo: Meta vs Resultado - {{ $curso->nome }}</h1>
    <div>
        <button onclick="toggleEditMode()" class="btn btn-success mr-2"><i class="fas fa-edit"></i> Editar Metas</button>
        <button onclick="saveMetaData()" class="btn btn-warning"><i class="fas fa-save"></i> Salvar Metas</button>
    </div>
</div>
@stop

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title">Métrica das metas e resultados gerais </h3>
    </div>
    <div class="card-body p-2">
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center mb-0">
                <thead class="bg-info text-white">
                    <tr>
                        <th rowspan="2">Período</th>
                        <th rowspan="2">Tipo</th>
                        <th rowspan="2">Turma</th>
                        <th rowspan="2">Alunos</th>
                        <th rowspan="2">Média Geral</th>
                        <th colspan="2">Frequência</th>
                        <th colspan="3">Aprovações</th>
                        <th colspan="3">Percentuais</th>
                        <th rowspan="2">IDE-SALA</th>
                    </tr>
                    <tr>
                        <th>Infrequência (%)</th>
                        <th>Frequência</th>
                        <th>LP</th>
                        <th>MT</th>
                        <th>Geral</th>
                        <th>% PT</th>
                        <th>% MAT</th>
                        <th>% Geral</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 1; $i <= 4; $i++)
                    @php
                        $meta = $metas[$i];
                        $resultado = $dadosPeriodos[$i] ?? null;
                    @endphp
                    <tr class="{{ $i == $periodoNumero ? 'table-active' : '' }}">
                        <td rowspan="2">{{ $i }}º</td>
                        <td class="bg-light font-weight-bold">Meta</td>
                        <td>
                            <input type="text" class="form-control form-control-sm meta-turma"
                                   data-periodo="{{ $i }}" value="{{ $meta->turma ?? $curso->nome }}"
                                   readonly>
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm meta-alunos"
                                   data-periodo="{{ $i }}" value="{{ $meta->alunos ?? 0 }}"
                                   min="0" readonly>
                        </td>
                        <td>
                            <input type="number" step="0.01" class="form-control form-control-sm meta-media"
                                   data-periodo="{{ $i }}" value="{{ $meta->media_geral ?? 0 }}"
                                   min="0" max="10" readonly>
                        </td>
                        <td>
                            <input type="number" step="0.01" class="form-control form-control-sm meta-infrequencia"
                                   data-periodo="{{ $i }}" value="{{ $meta->infrequencia ?? 0 }}"
                                   min="0" max="100" readonly>
                        </td>
                        <td>
                            <input type="number" step="0.01" class="form-control form-control-sm meta-frequencia"
                                   data-periodo="{{ $i }}" value="{{ number_format($meta->frequencia ?? 0, 2) }}"
                                   readonly>
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm meta-lp"
                                   data-periodo="{{ $i }}" value="{{ $meta->aprovacao_lp ?? 0 }}"
                                   min="0" readonly>
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm meta-mt"
                                   data-periodo="{{ $i }}" value="{{ $meta->aprovacao_mt ?? 0 }}"
                                   min="0" readonly>
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm meta-geral"
                                   data-periodo="{{ $i }}" value="{{ $meta->aprovacao_geral ?? 0 }}"
                                   min="0" readonly>
                        </td>
                        <td>
                            <input type="number" step="0.01" class="form-control form-control-sm meta-percentual-pt"
                                   data-periodo="{{ $i }}" value="{{ number_format($meta->percentual_pt ?? 0, 2) }}"
                                   readonly>
                        </td>
                        <td>
                            <input type="number" step="0.01" class="form-control form-control-sm meta-percentual-mat"
                                   data-periodo="{{ $i }}" value="{{ number_format($meta->percentual_mat ?? 0, 2) }}"
                                   readonly>
                        </td>
                        <td>
                            <input type="number" step="0.01" class="form-control form-control-sm meta-percentual-geral"
                                   data-periodo="{{ $i }}" value="{{ number_format($meta->percentual_geral ?? 0, 2) }}"
                                   readonly>
                        </td>
                        <td>
                            <input type="number" step="0.01" class="form-control form-control-sm meta-ide"
                                   data-periodo="{{ $i }}" value="{{ number_format($meta->ide_sala ?? 0, 2) }}"
                                   readonly>
                        </td>
                    </tr>
                    <tr class="{{ $i == $periodoNumero ? 'table-active' : '' }}">
                        <td class="bg-light font-weight-bold">Resultado</td>
                        <td>{{ $curso->nome }}</td>
                        <td class="{{ $resultado ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                            {{ $resultado->total_alunos ?? 'N/D' }}
                        </td>
                        <td class="{{ $resultado ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                            {{ $resultado ? number_format($resultado->media_geral, 2) : 'N/D' }}
                        </td>
                        <td class="{{ $resultado ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                            {{ $resultado ? number_format($resultado->infrequencia, 2) : 'N/D' }}
                        </td>
                        <td class="{{ $resultado ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                            {{ $resultado ? number_format($resultado->frequencia, 2) : 'N/D' }}
                        </td>
                        <td class="{{ $resultado ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                            {{ $resultado->acima_media_pt ?? 'N/D' }}
                        </td>
                        <td class="{{ $resultado ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                            {{ $resultado->acima_media_mat ?? 'N/D' }}
                        </td>
                        <td class="{{ $resultado ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                            {{ $resultado->acima_media_geral ?? 'N/D' }}
                        </td>
                        <td class="{{ $resultado ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                            {{ $resultado ? number_format($resultado->percentual_pt, 2) : 'N/D' }}
                        </td>
                        <td class="{{ $resultado ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                            {{ $resultado ? number_format($resultado->percentual_mat, 2) : 'N/D' }}
                        </td>
                        <td class="{{ $resultado ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                            {{ $resultado ? number_format($resultado->percentual_aprovacao_geral, 2) : 'N/D' }}
                        </td>
                        <td class="{{ $resultado ? 'bg-success text-white' : 'bg-secondary text-white' }}">
                            {{ $resultado ? number_format($resultado->media_total, 2) : 'N/D' }}
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
.table th, .table td { padding: 0.35rem; font-size: 0.85rem; vertical-align: middle; }
input[readonly] { background-color: #e6f7ff; cursor: not-allowed; font-weight: bold; }
input.form-control-sm { height: 32px; text-align: center; }
.table-active { background-color: #cce5ff !important; }
.bg-success { background-color: #28a745 !important; color:white; }
.bg-secondary { background-color: #6c757d !important; color:white; }
.table-responsive { box-shadow: 0 0 5px rgba(0,0,0,0.1); border-radius: 0.25rem; }
</style>
@stop

@section('js')
<script>
let isEditMode = false;
const cursoId = {{ $curso->id }};
const saveUrl = "{{ route('admin.periodos.salvar-metas') }}";

function toggleEditMode() {
    isEditMode = !isEditMode;
    document.querySelectorAll('.meta-alunos, .meta-media, .meta-infrequencia, .meta-lp, .meta-mt, .meta-geral, .meta-turma')
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
    const freq = (100 - inf) / 100;
    const percLP = alunos > 0 ? (lp / alunos) * 100 : 0;
    const percMT = alunos > 0 ? (mt / alunos) * 100 : 0;
    const percGeral = alunos > 0 ? (geral / alunos) * 100 : 0;

    // IDE-SALA simplificado (ajuste conforme sua fórmula específica)
    const ide = media * (freq / 100) * (percLP / 100) * (percMT / 100) * (percGeral / 100) * 10;

    // Atualizar campos calculados
    document.querySelector(`.meta-frequencia[data-periodo="${periodo}"]`).value = (freq * 100).toFixed(2);
    document.querySelector(`.meta-percentual-pt[data-periodo="${periodo}"]`).value = percLP.toFixed(2);
    document.querySelector(`.meta-percentual-mat[data-periodo="${periodo}"]`).value = percMT.toFixed(2);
    document.querySelector(`.meta-percentual-geral[data-periodo="${periodo}"]`).value = percGeral.toFixed(2);
    document.querySelector(`.meta-ide[data-periodo="${periodo}"]`).value = ide.toFixed(2);
}

async function saveMetaData() {
    if (!isEditMode) {
        alert('Ative o modo de edição primeiro!');
        return;
    }

    const metas = {};
    for (let i = 1; i <= 4; i++) {
        metas[i] = {
            periodo: i,
            turma: document.querySelector(`.meta-turma[data-periodo="${i}"]`).value,
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
        };
    }

    try {
        const response = await fetch(saveUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                curso_id: cursoId,
                metas: metas
            })
        });

        const result = await response.json();

        if (result.success) {
            alert('Metas salvas com sucesso!');
            toggleEditMode(); // Desativa o modo de edição
            location.reload(); // Recarrega a página para atualizar os dados
        } else {
            alert('Erro ao salvar metas: ' + result.message);
        }
    } catch (error) {
        alert('Erro ao salvar metas: ' + error.message);
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
