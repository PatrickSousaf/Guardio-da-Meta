function initPeriodosShow(routes, cursoId, periodo) {
    let isEditMode = false;

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
            const response = await fetch(routes.salvarDados, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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
            const response = await fetch(routes.importarCsv, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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

    // Expose functions to global scope if needed
    window.toggleEditMode = toggleEditMode;
    window.saveData = saveData;
    window.processCSV = processCSV;
    window.calcularTudo = calcularTudo;
    window.showNotification = showNotification;
    window.hideNotification = hideNotification;
}
