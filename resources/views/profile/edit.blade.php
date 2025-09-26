@extends('adminlte::page')

@section('title', 'Editar Perfil')

@section('content')
<br>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-edit mr-2"></i>Informações do Perfil
                    </h3>
                </div>
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="card-body">
                        <!-- Upload de Foto Centralizado -->
                        <div class="form-group">
                            <div class="row justify-content-center">
                                <div class="col-md-6 text-center">
                                    <label for="avatar" class="d-block mb-3">Foto do Perfil</label>

                                    <div class="avatar-preview mb-4">
                                        @if($user->avatar)
                                            <img id="avatarPreview" src="{{ asset('storage/' . $user->avatar) }}"
                                                 class="img-circle elevation-2" alt="Avatar Preview"
                                                 style="width: 150px; height: 150px; object-fit: cover;">
                                        @else
                                            <img id="avatarPreview" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=007bff&color=fff&size=150&bold=true"
                                                 class="img-circle elevation-2" alt="Avatar Preview"
                                                 style="width: 150px; height: 150px; object-fit: cover;">
                                        @endif
                                    </div>

                                    <div class="d-flex flex-column gap-2 mx-auto" style="max-width: 200px;">
                                        <label for="avatar" class="btn btn-primary btn-sm w-100">
                                            <i class="fas fa-camera mr-1"></i> Escolher Foto
                                        </label>
                                        <button type="button" id="removeAvatar" class="btn btn-outline-danger btn-sm w-100">
                                            <i class="fas fa-trash mr-1"></i> Remover
                                        </button>
                                    </div>

                                    <input type="file" id="avatar" name="avatar" accept="image/*"
                                           class="d-none" onchange="previewImage(this)">
                                    <input type="hidden" name="remove_avatar" id="removeAvatarInput" value="0">

                                    @error('avatar')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                    <small class="form-text text-muted d-block mt-2">
                                        Formatos permitidos: JPG, PNG, GIF. Tamanho máximo: 2MB
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Nome -->
                        <div class="form-group">
                            <label for="name">Nome Completo</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $user->name) }}"
                                       placeholder="Digite seu nome completo" required>
                            </div>
                            @error('name')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">Email</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email', $user->email) }}"
                                       placeholder="Digite seu email" required>
                            </div>
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Telefone -->
                        <div class="form-group">
                            <label for="phone">Telefone</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                </div>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone" value="{{ old('phone', $user->phone ?? '') }}"
                                       placeholder="(00) 00000-0000">
                            </div>
                            @error('phone')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save mr-2"></i>Salvar Alterações
                                </button>
                                <a href="{{ url()->previous() }}" class="btn btn-default btn-lg">
                                    <i class="fas fa-times mr-2"></i>Cancelar
                                </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <a href="{{ route('password.edit') }}" class="btn btn-warning">
                                    <i class="fas fa-key mr-2"></i>Alterar Senha
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .avatar-preview {
            position: relative;
        }

        .avatar-preview img {
            transition: all 0.3s ease;
            border: 3px solid #dee2e6;
        }

        .avatar-preview:hover img {
            border-color: #007bff;
            transform: scale(1.05);
        }

        .preview-avatar img {
            transition: transform 0.3s ease;
        }

        .preview-avatar:hover img {
            transform: rotate(5deg);
        }

        input[type="file"] {
            cursor: pointer;
        }

        .card-primary {
            border-top: 3px solid #007bff;
        }

        /* Estilos específicos para os botões de upload */
        .btn-upload-group .btn {
            border-radius: 0.375rem;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
        }

        .btn-upload-group .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .btn-upload-group .btn-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
        }

        .btn-upload-group .btn-outline-danger {
            border: 1px solid #dc3545;
            color: #dc3545;
        }

        .btn-upload-group .btn-outline-danger:hover {
            background-color: #dc3545;
            color: white;
        }

        /* Centralização adicional */
        .justify-content-center {
            justify-content: center;
        }

        .mx-auto {
            margin-left: auto;
            margin-right: auto;
        }
    </style>
@stop

@section('js')
    <script>
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
                const defaultAvatar = 'https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=007bff&color=fff&size=150&bold=true';
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
                    liveName.textContent = this.value || '{{ $user->name }}';
                }
            });

            // Preview em tempo real do email
            document.getElementById('email').addEventListener('input', function() {
                const liveEmail = document.getElementById('liveEmail');
                if (liveEmail) {
                    liveEmail.textContent = this.value || '{{ $user->email }}';
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
    </script>
@stop
