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
                    <div class="table-responsive">
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
<style>
/* Estilos da Notificação */
.notification-success {
    position: fixed;
    top: 20px;
    right: 20px;
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    padding: 15px 20px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    z-index: 10000;
    max-width: 350px;
    display: none;
    animation: slideInRight 0.5s ease-out;
    border-left: 5px solid #fff;
}

.notification-success.show {
    display: block;
    animation: slideInRight 0.5s ease-out;
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 15px;
}

.notification-icon {
    font-size: 2rem;
    animation: bounce 0.6s ease-in-out;
}

.notification-text h4 {
    margin: 0 0 5px 0;
    font-weight: bold;
    font-size: 1.1rem;
}

.notification-text p {
    margin: 0;
    opacity: 0.9;
    font-size: 0.9rem;
}

.notification-close {
    background: none;
    border: none;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    padding: 5px;
    margin-left: auto;
    opacity: 0.7;
    transition: opacity 0.3s;
}

.notification-close:hover {
    opacity: 1;
}

/* Animações */
@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

/* Efeito de progresso (opcional) */
.notification-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    background: rgba(255,255,255,0.5);
    width: 100%;
    animation: progress 5s linear;
}

@keyframes progress {
    from { width: 100%; }
    to { width: 0%; }
}
</style>
@stop

@section('js')
<script>
    let isEditMode = false;
    const cursoId = {{ $curso->id }};
    const periodo = {{ $periodoNumero }};

    // Atualizar o nome do arquivo selecionado no input
    document.getElementById('csvFileInput').addEventListener('change', function(e) {
        var fileName = e.target.files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });

    function toggleEditMode() {
        isEditMode = !isEditMode;
        const inputs = document.querySelectorAll('input[type="number"]');
        inputs.forEach(input => {
            if (!['frequencia', 'percentualPT', 'percentualMat', 'percentualAprovacaoGeral', 'mediaTotal'].includes(input.id)) {
                input.readOnly = !isEditMode;
                if (isEditMode) {
                    input.classList.add('metric-input-editing');
                } else {
                    input.classList.remove('metric-input-editing');
                }
            }
        });

        const editBtn = document.querySelector('button[onclick="toggleEditMode()"]');
        if (isEditMode) {
            editBtn.classList.remove('btn-success');
            editBtn.classList.add('btn-danger');
            editBtn.innerHTML = '<i class="fas fa-times mr-1"></i> Cancelar Edição';
        } else {
            editBtn.classList.remove('btn-danger');
            editBtn.classList.add('btn-success');
            editBtn.innerHTML = '<i class="fas fa-edit mr-1"></i> Editar';
        }

        // Notificação simples para modo edição
        showNotification('Modo ' + (isEditMode ? 'edição' : 'visualização') + ' ativado', 'info');
    }

    function showNotification(message, type = 'success') {
        // Para simplificar, vou usar a notificação existente com mensagem customizada
        const notification = document.getElementById('successNotification');
        const icon = notification.querySelector('.notification-icon i');
        const title = notification.querySelector('.notification-text h4');
        const text = notification.querySelector('.notification-text p');

        if (type === 'info') {
            notification.style.background = 'linear-gradient(135deg, #17a2b8, #6f42c1)';
            icon.className = 'fas fa-info-circle';
            title.innerHTML = 'ℹ️ ' + message;
            text.innerHTML = 'Altere os valores conforme necessário.';
        } else {
            notification.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
            icon.className = 'fas fa-check-circle';
            title.innerHTML = '✅ ' + message;
            text.innerHTML = 'As informações foram atualizadas no sistema.';
        }

        notification.classList.add('show');

        // Auto-esconder após 5 segundos
        setTimeout(() => {
            notification.classList.remove('show');
        }, 5000);
    }

    function hideNotification() {
        document.getElementById('successNotification').classList.remove('show');
    }

    function calcularTudo() {
        console.log('Calculando tudo...');

        // Calcular frequência (0-1)
        const infrequencia = parseFloat(document.getElementById('infrequencia').value) || 0;
        const frequencia = (100 - infrequencia) / 100;
        document.getElementById('frequencia').value = frequencia.toFixed(2);
        console.log('Frequência:', frequencia.toFixed(2));

        // Calcular percentuais (0-1)
        const alunos = parseFloat(document.getElementById('TotalAlunos').value) || 0;
        const acimaMediaPT = parseFloat(document.getElementById('acimaMediaPT').value) || 0;
        const acimaMediaMat = parseFloat(document.getElementById('acimaMediaMat').value) || 0;
        const acimaMediaGeral = parseFloat(document.getElementById('acimaMediaGeral').value) || 0;

        const percentualPT = alunos > 0 ? acimaMediaPT / alunos : 0;
        const percentualMat = alunos > 0 ? acimaMediaMat / alunos : 0;
        const percentualGeral = alunos > 0 ? acimaMediaGeral / alunos : 0;

        document.getElementById('percentualPT').value = percentualPT.toFixed(2);
        document.getElementById('percentualMat').value = percentualMat.toFixed(2);
        document.getElementById('percentualAprovacaoGeral').value = percentualGeral.toFixed(2);

        console.log('Percentuais - PT:', percentualPT.toFixed(2), 'MAT:', percentualMat.toFixed(2), 'GERAL:', percentualGeral.toFixed(2));

        // Calcular IDE Sala - garantindo 2 casas decimais
        const mediaGeral = parseFloat(document.getElementById('mediaGeral').value) || 0;
        const ideSala = mediaGeral * frequencia * percentualPT * percentualMat * percentualGeral;
        const ideSalaFormatado = parseFloat(ideSala.toFixed(2)); // Força 2 casas decimais
        document.getElementById('mediaTotal').value = ideSalaFormatado;

        console.log('IDE Sala calculado:', ideSala, 'Formatado:', ideSalaFormatado);
    }

    async function saveData() {
        console.log('Iniciando salvamento...');

        const data = {
            curso_id: cursoId,
            periodo: periodo,
            total_alunos: parseFloat(document.getElementById('TotalAlunos').value) || 0,
            media_geral: parseFloat(document.getElementById('mediaGeral').value) || 0,
            infrequencia: parseFloat(document.getElementById('infrequencia').value) || 0,
            frequencia: parseFloat(document.getElementById('frequencia').value) || 0,
            acima_media_pt: parseFloat(document.getElementById('acimaMediaPT').value) || 0,
            acima_media_mat: parseFloat(document.getElementById('acimaMediaMat').value) || 0,
            percentual_pt: parseFloat(document.getElementById('percentualPT').value) || 0,
            percentual_mat: parseFloat(document.getElementById('percentualMat').value) || 0,
            acima_media_geral: parseFloat(document.getElementById('acimaMediaGeral').value) || 0,
            percentual_aprovacao_geral: parseFloat(document.getElementById('percentualAprovacaoGeral').value) || 0,
            media_total: parseFloat(document.getElementById('mediaTotal').value) || 0
        };

        console.log('Dados a serem enviados:', data);

        try {
            const response = await fetch('{{ route('admin.periodos.salvar-dados') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            console.log('Resposta recebida, status:', response.status);

            // Verificar se a resposta é JSON
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                const result = await response.json();
                console.log('Resultado JSON:', result);

                if (result.success) {
                    // MOSTRAR NOTIFICAÇÃO PERSONALIZADA
                    showNotification('Dados salvos com sucesso!', 'success');
                } else {
                    showNotification('Erro ao salvar dados: ' + result.message, 'error');
                }
            } else {
                const textResult = await response.text();
                console.log('Resposta como texto:', textResult);

                if (response.ok) {
                    showNotification('Dados salvos com sucesso!', 'success');
                } else {
                    showNotification('Erro inesperado ao salvar', 'error');
                }
            }
        } catch (error) {
            console.error('Erro na requisição:', error);
            showNotification('Erro de conexão: ' + error.message, 'error');
        }
    }

    async function processCSV() {
        const fileInput = document.getElementById('csvFileInput');
        const file = fileInput.files[0];

        if (!file) {
            showNotification('Por favor, selecione um arquivo CSV primeiro.', 'warning');
            return;
        }

        const formData = new FormData();
        formData.append('file', file);
        formData.append('curso_id', cursoId);
        formData.append('periodo', periodo);

        try {
            const response = await fetch('{{ route("admin.periodos.importar-csv") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                document.getElementById('TotalAlunos').value = result.data.TotalAlunos;
                document.getElementById('mediaGeral').value = result.data.mediaGeral;
                document.getElementById('acimaMediaPT').value = result.data.acimaMediaPT;
                document.getElementById('acimaMediaMat').value = result.data.acimaMediaMat;
                document.getElementById('acimaMediaGeral').value = result.data.acimaMediaGeral;

                calcularTudo();
                showNotification('CSV processado com sucesso!', 'success');
            } else {
                showNotification('Erro ao processar CSV: ' + result.message, 'error');
            }
        } catch (error) {
            showNotification('Erro ao processar CSV: ' + error.message, 'error');
        }
    }
</script>
@stop
