@extends('adminlte::page')

@section('title', $curso->nome . ' - ' . $ano . ' - ' . $periodo)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1><i class="fas fa-chart-line mr-2"></i>{{ $periodo }} - Visão Geral</h1>
        <div>
            <button onclick="toggleEditMode()" class="btn btn-success mr-2"><i class="fas fa-edit"></i> Editar</button>
            <button onclick="saveData()" class="btn btn-warning"><i class="fas fa-save"></i> Salvar Dados</button>
        </div>
    </div>
@stop

@section('content')
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

        Swal.fire({
            icon: 'info',
            title: isEditMode ? 'Modo edição ativado' : 'Modo visualização ativado',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });
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
                    Swal.fire({
                        icon: 'success',
                        title: '✅ Dados salvos com sucesso!',
                        text: 'As informações do período foram atualizadas.',
                        confirmButtonText: 'OK',
                        timer: 4000,
                        timerProgressBar: true,
                        showConfirmButton: true
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro ao salvar',
                        text: result.message || 'Ocorreu um erro ao salvar os dados.',
                        confirmButtonText: 'OK'
                    });
                }
            } else {
                // Se não for JSON, tentar ler como texto
                const textResult = await response.text();
                console.log('Resposta como texto:', textResult);

                Swal.fire({
                    icon: response.ok ? 'success' : 'error',
                    title: response.ok ? '✅ Dados salvos!' : 'Erro',
                    text: response.ok ? 'Operação realizada com sucesso.' : 'Resposta inesperada do servidor.',
                    confirmButtonText: 'OK'
                });
            }
        } catch (error) {
            console.error('Erro na requisição:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erro de conexão',
                text: 'Erro ao conectar com o servidor: ' + error.message,
                confirmButtonText: 'OK'
            });
        }
    }

    // Função para processar o conteúdo do CSV
    // Substitua a função processCSV() por esta versão corrigida
async function processCSV() {
    const fileInput = document.getElementById('csvFileInput');
    const file = fileInput.files[0];

    if (!file) {
        Swal.fire('Atenção', 'Por favor, selecione um arquivo CSV primeiro.', 'warning');
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
            // Preencher os campos com os dados processados
            document.getElementById('TotalAlunos').value = result.data.TotalAlunos;
            document.getElementById('mediaGeral').value = result.data.mediaGeral;
            document.getElementById('acimaMediaPT').value = result.data.acimaMediaPT;
            document.getElementById('acimaMediaMat').value = result.data.acimaMediaMat;
            document.getElementById('acimaMediaGeral').value = result.data.acimaMediaGeral;

            calcularTudo();

            Swal.fire({
                icon: 'success',
                title: 'CSV processado com sucesso!',
                text: result.message,
                confirmButtonText: 'OK'
            });
        } else {
            Swal.fire('Erro', result.message, 'error');
        }
    } catch (error) {
        Swal.fire('Erro', 'Erro ao processar CSV: ' + error.message, 'error');
    }
}
</script>
@stop
