function initPeriodosComparativo(cursoId, saveUrl) {
    let isEditMode = false;

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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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

    // Expose functions to global scope if needed
    window.toggleEditMode = toggleEditMode;
    window.saveMetaData = saveMetaData;
    window.calcularMeta = calcularMeta;
}
