function initProfileEdit(userName, userEmail) {
    function previewImage(input) {
        const preview = document.getElementById('avatarPreview');
        const livePreview = document.getElementById('livePreview');
        const file = input.files[0];

        if (file) {
            document.getElementById('removeAvatarInput').value = '0';
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                if (livePreview) {
                    livePreview.src = e.target.result;
                }
            }

            reader.readAsDataURL(file);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Remover avatar
        document.getElementById('removeAvatar').addEventListener('click', function() {
            document.getElementById('avatar').value = '';
            document.getElementById('removeAvatarInput').value = '1';
            const defaultAvatar = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(userName) + '&background=007bff&color=fff&size=150&bold=true';
            document.getElementById('avatarPreview').src = defaultAvatar;

            const livePreview = document.getElementById('livePreview');
            if (livePreview) {
                livePreview.src = defaultAvatar;
            }
        });

        // Preview em tempo real do nome
        document.getElementById('name').addEventListener('input', function() {
            const liveName = document.getElementById('liveName');
            if (liveName) {
                liveName.textContent = this.value || userName;
            }
        });

        // Preview em tempo real do email
        document.getElementById('email').addEventListener('input', function() {
            const liveEmail = document.getElementById('liveEmail');
            if (liveEmail) {
                liveEmail.textContent = this.value || userEmail;
            }
        });

        // Validação de tamanho de arquivo
        document.getElementById('avatar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.size > 2 * 1024 * 1024) {
                alert('O arquivo é muito grande. Por favor, selecione uma imagem menor que 2MB.');
                this.value = '';
            }
        });

        // Loading ao submeter o formulário
        document.querySelector('form').addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Salvando...';
            submitBtn.disabled = true;
        });

        // Máscara para telefone
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                if (value.length <= 10) {
                    value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                } else {
                    value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                }
                e.target.value = value;
            }
        });
    });

    // Expose function
    window.previewImage = previewImage;
}
