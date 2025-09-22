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
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-file-import mr-2"></i>Importar Dados</h3>
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

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success">
                    <h3 class="card-title"><i class="fas fa-table mr-2"></i>Métricas da Turma</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center">
                            <thead class="bg-lightblue">
                                <tr>
                                    <th style="width: 40%">Métrica</th>
                                    <th style="width: 60%">Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-start font-weight-bold">Total de Alunos</td>
                                    <td><input type="number" id="TotalAlunos" class="form-control text-center font-weight-bold" value="{{ $dados->total_alunos ?? 0 }}" oninput="calcularTudo()"></td>
                                </tr>
                                <tr>
                                    <td class="text-start font-weight-bold">Média Geral</td>
                                    <td><input type="number" step="0.01" id="mediaGeral" class="form-control text-center font-weight-bold" value="{{ number_format($dados->media_geral ?? 0, 2) }}" oninput="calcularTudo()"></td>
                                </tr>
                                <tr>
                                    <td class="text-start font-weight-bold">Infrequência (%)</td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" step="0.01" id="infrequencia" class="form-control text-center font-weight-bold" value="{{ number_format($dados->infrequencia ?? 0, 2) }}" oninput="calcularTudo()">
                                            <div class="input-group-append">
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-start font-weight-bold">Frequência</td>
                                    <td><input type="number" step="0.01" id="frequencia" class="form-control text-center text-success font-weight-bold" value="{{ number_format($dados->frequencia ?? 0.85, 2) }}" readonly></td>
                                </tr>
                                <tr class="bg-light">
                                    <td class="text-start font-weight-bold text-primary">Acima da Média em Português</td>
                                    <td><input type="number" id="acimaMediaPT" class="form-control text-center text-primary font-weight-bold" value="{{ $dados->acima_media_pt ?? 0 }}" oninput="calcularTudo()"></td>
                                </tr>
                                <tr class="bg-light">
                                    <td class="text-start font-weight-bold text-primary">Acima da Média em Matemática</td>
                                    <td><input type="number" id="acimaMediaMat" class="form-control text-center text-primary font-weight-bold" value="{{ $dados->acima_media_mat ?? 0 }}" oninput="calcularTudo()"></td>
                                </tr>
                                <tr class="bg-light">
                                    <td class="text-start font-weight-bold text-primary">Acima da Média Geral</td>
                                    <td><input type="number" id="acimaMediaGeral" class="form-control text-center text-primary font-weight-bold" value="{{ $dados->acima_media_geral ?? 0 }}" oninput="calcularTudo()"></td>
                                </tr>
                                <tr>
                                    <td class="text-start font-weight-bold text-info">Percentual de Português (0-1)</td>
                                    <td><input type="number" step="0.01" id="percentualPT" class="form-control text-center text-info font-weight-bold" value="{{ number_format($dados->percentual_pt ?? 0, 2) }}" readonly></td>
                                </tr>
                                <tr>
                                    <td class="text-start font-weight-bold text-info">Percentual de Matemática (0-1)</td>
                                    <td><input type="number" step="0.01" id="percentualMat" class="form-control text-center text-info font-weight-bold" value="{{ number_format($dados->percentual_mat ?? 0, 2) }}" readonly></td>
                                </tr>
                                <tr>
                                    <td class="text-start font-weight-bold text-info">Percentual Geral (0-1)</td>
                                    <td><input type="number" step="0.01" id="percentualAprovacaoGeral" class="form-control text-center text-info font-weight-bold" value="{{ number_format($dados->percentual_aprovacao_geral ?? 0, 2) }}" readonly></td>
                                </tr>
                                <tr class="table-primary">
                                    <td class="text-start font-weight-bold display-6"><i class="fas fa-star mr-2"></i>IDE Sala</td>
                                    <td>
                                        <input type="number" step="0.01" id="mediaTotal" class="form-control text-center font-weight-bold text-white bg-primary" value="{{ number_format($dados->media_total ?? 0, 2) }}" readonly>
                                        <small class="form-text">Índice de Desempenho Educacional</small>
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
    <style>
        input[readonly] {
            background-color: #f8f9fa;
            cursor: not-allowed;
        }
        .table-primary {
            background-color: #cfe2ff;
        }
        .bg-lightblue {
            background-color: #e3f2fd;
        }
        .font-weight-bold {
            font-weight: 700 !important;
        }
        .card-title {
            font-weight: 600;
        }
        .custom-file-label::after {
            content: "Procurar";
        }
        #mediaTotal {
            font-size: 1.2rem;
            height: 45px;
        }
        .display-6 {
            font-size: 1.4rem;
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
            // Não deixar editar campos calculados automaticamente
            if (!['frequencia', 'percentualPT', 'percentualMat', 'percentualAprovacaoGeral', 'mediaTotal'].includes(input.id)) {
                input.readOnly = !isEditMode;
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
        // Calcular frequência (0-1)
        const infrequencia = parseFloat(document.getElementById('infrequencia').value) || 0;
        const frequencia = (100 - infrequencia) / 100;
        document.getElementById('frequencia').value = frequencia.toFixed(2);

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

        // Calcular IDE Sala
        const mediaGeral = parseFloat(document.getElementById('mediaGeral').value) || 0;
        
        const ideSala = mediaGeral * frequencia * percentualPT * percentualMat * percentualGeral;
        document.getElementById('mediaTotal').value = ideSala.toFixed(2);
    }

    async function saveData() {
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

        try {
            const response = await fetch('{{ route('admin.periodos.salvar-dados') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Dados salvos com sucesso!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                Swal.fire('Erro', result.message, 'error');
            }
        } catch (error) {
            Swal.fire('Erro', 'Erro de conexão: ' + error.message, 'error');
        }
    }

    // Função para processar o conteúdo do CSV
    function processarCSV(csvContent) {
        const linhas = csvContent.split('\n');
        const cabecalhos = linhas[0].split(';').map(h => h.trim());
        
        // Encontrar índices das colunas relevantes
        const indicePortugues = cabecalhos.findIndex(h => 
            h.includes('PORTUGUESA') || h.includes('PORTUGUES') || h.includes('LÕNGUA PORTUGUESA')
        );
        
        const indiceMatematica = cabecalhos.findIndex(h => 
            h.includes('MATEM') || h.includes('MATEMÁTICA') || h.includes('MATEM¡TICA')
        );
        
        // Verificar se encontrou as colunas necessárias
        if (indicePortugues === -1 || indiceMatematica === -1) {
            throw new Error('Colunas de Português ou Matemática não encontradas no CSV');
        }
        
        let totalAlunos = 0;
        let somaNotas = 0;
        let acimaMediaPT = 0;
        let acimaMediaMat = 0;
        let acimaMediaGeral = 0;
        
        // Processar cada linha de aluno (ignorando o cabeçalho)
        for (let i = 1; i < linhas.length; i++) {
            const linha = linhas[i].trim();
            if (!linha) continue; // Pular linhas vazias
            
            const valores = linha.split(';').map(v => v.trim());
            
            // Verificar se é uma linha de aluno (primeira coluna deve ser um número)
            if (!valores[0] || isNaN(valores[0])) continue;
            
            totalAlunos++;
            
            // Calcular média do aluno
            let somaNotasAluno = 0;
            let totalNotasAluno = 0;
            
            for (let j = 2; j < valores.length; j++) {
                if (valores[j] && !isNaN(valores[j]) && valores[j] !== '') {
                    // Converter para número com duas casas decimais
                    const nota = parseFloat(valores[j]);
                    somaNotasAluno += nota;
                    totalNotasAluno++;
                }
            }
            
            const mediaAluno = totalNotasAluno > 0 ? somaNotasAluno / totalNotasAluno : 0;
            somaNotas += mediaAluno;
            
            // Verificar se está acima da média em Português
            if (valores[indicePortugues] && !isNaN(valores[indicePortugues])) {
                const notaPT = parseFloat(valores[indicePortugues]);
                if (notaPT >= 7.0) {
                    acimaMediaPT++;
                }
            }
            
            // Verificar se está acima da média em Matemática
            if (valores[indiceMatematica] && !isNaN(valores[indiceMatematica])) {
                const notaMat = parseFloat(valores[indiceMatematica]);
                if (notaMat >= 7.0) {
                    acimaMediaMat++;
                }
            }
            
            // Verificar se está acima da média geral (média >= 7.0)
            if (mediaAluno >= 7.0) {
                acimaMediaGeral++;
            }
        }
        
        const mediaGeral = totalAlunos > 0 ? somaNotas / totalAlunos : 0;
        
        return {
            TotalAlunos: totalAlunos,
            mediaGeral: mediaGeral.toFixed(2),
            acimaMediaPT: acimaMediaPT,
            acimaMediaMat: acimaMediaMat,
            acimaMediaGeral: acimaMediaGeral
        };
    }

    function processCSV() {
        const fileInput = document.getElementById('csvFileInput');
        const file = fileInput.files[0];
        
        if (!file) {
            Swal.fire('Atenção', 'Por favor, selecione um arquivo CSV primeiro.', 'warning');
            return;
        }

        const reader = new FileReader();
        
        reader.onload = function(e) {
            try {
                const csvContent = e.target.result;
                const dadosProcessados = processarCSV(csvContent);
                
                // Preencher os campos com os dados processados
                document.getElementById('TotalAlunos').value = dadosProcessados.TotalAlunos;
                document.getElementById('mediaGeral').value = dadosProcessados.mediaGeral;
                document.getElementById('acimaMediaPT').value = dadosProcessados.acimaMediaPT;
                document.getElementById('acimaMediaMat').value = dadosProcessados.acimaMediaMat;
                document.getElementById('acimaMediaGeral').value = dadosProcessados.acimaMediaGeral;
                
                // Recalcular tudo
                calcularTudo();
                
                Swal.fire({
                    icon: 'success',
                    title: 'CSV processado com sucesso!',
                    text: `Foram importados dados de ${dadosProcessados.TotalAlunos} alunos.`,
                    confirmButtonText: 'OK'
                });
            } catch (error) {
                Swal.fire('Erro', 'Erro ao processar CSV: ' + error.message, 'error');
            }
        };
        
        reader.onerror = function() {
            Swal.fire('Erro', 'Erro ao ler o arquivo', 'error');
        };
        
        reader.readAsText(file);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Calcular tudo ao carregar a página
        calcularTudo();

        // Adicionar event listeners para campos editáveis
        const camposEditaveis = ['TotalAlunos', 'infrequencia', 'acimaMediaPT', 'acimaMediaMat', 'acimaMediaGeral', 'mediaGeral'];
        camposEditaveis.forEach(id => {
            document.getElementById(id).addEventListener('input', calcularTudo);
        });
    });
</script>
@stop