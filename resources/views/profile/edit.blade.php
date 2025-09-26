@extends('adminlte::page')

@section('title', 'Editar Perfil')

@section('content_header')
    <h1>Editar Perfil</h1>
@stop

@section('content')
    <div class="row">
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
                        <!-- Upload de Foto -->
                        <div class="form-group">
                            <label for="avatar">Foto do Perfil</label>
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <div class="avatar-preview mb-3">
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
                                    <div class="btn-group">
                                        <label for="avatar" class="btn btn-primary btn-sm">
                                            <i class="fas fa-camera mr-1"></i> Escolher Foto
                                        </label>
                                        <button type="button" id="removeAvatar" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash mr-1"></i> Remover
                                        </button>
                                    </div>
                                    <input type="file" id="avatar" name="avatar" accept="image/*"
                                           class="d-none" onchange="previewImage(this)">
                                    @error('avatar')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted d-block mt-2">
                                        Formatos permitidos: JPG, PNG, GIF. Tamanho máximo: 2MB
                                    </small>
                                </div>
                                <div class="col-md-8">
                                    <div class="alert alert-info">
                                        <h6><i class="fas fa-info-circle mr-2"></i>Dicas para uma boa foto:</h6>
                                        <ul class="mb-0 pl-3">
                                            <li>Use uma imagem quadrada para melhor qualidade</li>
                                            <li>Evite fotos muito escuras ou claras</li>
                                            <li>O rosto deve estar visível e centralizado</li>
                                        </ul>
                                    </div>
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

                        <!-- Telefone (campo adicional) -->
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

                        <!-- Bio (campo adicional) -->
                        <div class="form-group">
                            <label for="bio">Biografia</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" id="bio"
                                      name="bio" rows="3" placeholder="Conte um pouco sobre você..."
                                      maxlength="255">{{ old('bio', $user->bio ?? '') }}</textarea>
                            @error('bio')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text text-muted">Máximo de 255 caracteres</small>
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

        <div class="col-md-4">
            <!-- Preview Card -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-eye mr-2"></i>Pré-visualização
                    </h3>
                </div>
                <div class="card-body text-center">
                    <div class="preview-avatar mb-3">
                        <img id="livePreview" src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=007bff&color=fff&size=150&bold=true' }}"
                             class="img-circle elevation-3" style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                    <h4 id="liveName">{{ $user->name }}</h4>
                    <p class="text-muted" id="liveEmail">{{ $user->email }}</p>
                    <span class="badge badge-{{ $user->role == 'director' ? 'danger' : ($user->role == 'management' ? 'warning' : 'success') }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>

            <!-- Dicas Card -->
            <div class="card card-secondary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lightbulb mr-2"></i>Dicas
                    </h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            Mantenha suas informações atualizadas
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            Use uma foto que te represente bem
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            A biografia ajuda na identificação
                        </li>
                    </ul>
                </div>
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

        .btn-group .btn {
            border-radius: 0.25rem;
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

        .card-info {
            border-top: 3px solid #17a2b8;
        }

        .card-secondary {
            border-top: 3px solid #6c757d;
        }

        .character-count {
            font-size: 0.8rem;
            text-align: right;
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
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    livePreview.src = e.target.result;
                }

                reader.readAsDataURL(file);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Remover avatar
            document.getElementById('removeAvatar').addEventListener('click', function() {
                document.getElementById('avatar').value = '';
                const defaultAvatar = 'https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=007bff&color=fff&size=150&bold=true';
                document.getElementById('avatarPreview').src = defaultAvatar;
                document.getElementById('livePreview').src = defaultAvatar;
            });

            // Preview em tempo real do nome
            document.getElementById('name').addEventListener('input', function() {
                document.getElementById('liveName').textContent = this.value || '{{ $user->name }}';
            });

            // Preview em tempo real do email
            document.getElementById('email').addEventListener('input', function() {
                document.getElementById('liveEmail').textContent = this.value || '{{ $user->email }}';
            });

            // Contador de caracteres para biografia
            const bioTextarea = document.getElementById('bio');
            if (bioTextarea) {
                // Adiciona contador de caracteres
                const charCount = document.createElement('div');
                charCount.className = 'character-count text-muted mt-1';
                charCount.innerHTML = `<span id="charCount">${bioTextarea.value.length}</span>/255 caracteres`;
                bioTextarea.parentNode.appendChild(charCount);

                bioTextarea.addEventListener('input', function() {
                    document.getElementById('charCount').textContent = this.value.length;

                    if (this.value.length > 255) {
                        charCount.classList.add('text-danger');
                        charCount.classList.remove('text-muted');
                    } else {
                        charCount.classList.remove('text-danger');
                        charCount.classList.add('text-muted');
                    }
                });
            }

            // Validação de tamanho de arquivo
            document.getElementById('avatar').addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file && file.size > 2 * 1024 * 1024) { // 2MB
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

            // Máscara simples para telefone
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
