@extends('adminlte::page')

@section('title', 'Perfil')

@section('content_header')
    <h1>Meu Perfil</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-4">
            <!-- Profile Card -->
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        @if($user->avatar)
                            <img class="profile-user-img img-fluid img-circle" src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}">
                        @else
                            <img class="profile-user-img img-fluid img-circle" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=007bff&color=fff&size=150&bold=true" alt="{{ $user->name }}">
                        @endif
                    </div>

                    <h3 class="profile-username text-center">{{ $user->name }}</h3>

                    <p class="text-muted text-center">
                        <span class="badge badge-{{ $user->role == 'director' ? 'danger' : ($user->role == 'management' ? 'warning' : 'success') }}">
                            <i class="fas fa-{{ $user->role == 'director' ? 'crown' : ($user->role == 'management' ? 'briefcase' : 'chalkboard-teacher') }} mr-1"></i>
                            {{ ucfirst($user->role) }}
                        </span>
                    </p>

                    <ul class="list-group list-group-unbordered mb-3">
                        <li class="list-group-item">
                            <b>Email</b> <a class="float-right">{{ $user->email }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Cadastrado em</b> <a class="float-right">{{ $user->created_at->format('d/m/Y') }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>Último Login</b> <a class="float-right">{{ $user->updated_at->format('d/m/Y') }}</a>
                        </li>
                        <li class="list-group-item">
                            <b>ID do Usuário</b> <a class="float-right">#{{ $user->id }}</a>
                        </li>
                    </ul>

                    <div class="text-center">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-edit mr-2"></i>Editar Perfil
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-default btn-block">
                            <i class="fas fa-tachometer-alt mr-2"></i>Voltar ao Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Password Update Card -->
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-key mr-2"></i>Alterar Senha
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Certifique-se de que sua conta está usando uma senha longa e aleatória para permanecer segura.
                    </p>

                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="form-group">
                            <label for="update_password_current_password">Senha Atual</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input type="password" id="update_password_current_password" name="current_password"
                                       class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                       placeholder="Digite sua senha atual" autocomplete="current-password">
                            </div>
                            @error('current_password', 'updatePassword')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="update_password_password">Nova Senha</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                                </div>
                                <input type="password" id="update_password_password" name="password"
                                       class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                       placeholder="Digite a nova senha" autocomplete="new-password">
                            </div>
                            @error('password', 'updatePassword')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="update_password_password_confirmation">Confirmar Nova Senha</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-check-circle"></i></span>
                                </div>
                                <input type="password" id="update_password_password_confirmation" name="password_confirmation"
                                       class="form-control" placeholder="Confirme a nova senha" autocomplete="new-password">
                            </div>
                            @error('password_confirmation', 'updatePassword')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="fas fa-save mr-2"></i>Salvar Nova Senha
                            </button>

                            @if (session('status') === 'password-updated')
                                <span class="text-success ml-3">
                                    <i class="fas fa-check-circle mr-1"></i>Senha atualizada com sucesso!
                                </span>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Additional Info Cards -->
            <div class="row">
                <div class="col-md-6">
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Email Verificado</span>
                            <span class="info-box-number">
                                {{ $user->email_verified_at ? 'Sim' : 'Não' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box bg-gradient-success">
                        <span class="info-box-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </span>
                        <div class="info-box-content">
                            <span class="info-box-text">Membro há</span>
                            <span class="info-box-number">{{ $user->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .profile-user-img {
            border: 3px solid #adb5bd;
            margin: 0 auto;
            padding: 3px;
            width: 150px;
            height: 150px;
            transition: all 0.3s ease;
        }

        .profile-user-img:hover {
            border-color: #007bff;
            transform: scale(1.05);
        }

        .card-primary.card-outline {
            border-top: 3px solid #007bff;
        }

        .card-warning.card-outline {
            border-top: 3px solid #ffc107;
        }

        .list-group-item {
            border-left: 0;
            border-right: 0;
            padding: 0.75rem 0;
        }

        .list-group-item:first-child {
            border-top: 0;
        }

        .list-group-item:last-child {
            border-bottom: 0;
        }

        .info-box {
            cursor: pointer;
            transition: transform 0.2s;
        }

        .info-box:hover {
            transform: translateY(-5px);
        }

        .btn-block {
            margin-bottom: 10px;
        }
    </style>
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Adiciona efeito de loading ao salvar senha
            const passwordForm = document.querySelector('form[action="{{ route('password.update') }}"]');
            if (passwordForm) {
                passwordForm.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Salvando...';
                    submitBtn.disabled = true;
                });
            }

            // Mostra/oculta senha
            const togglePassword = document.createElement('span');
            togglePassword.className = 'input-group-text';
            togglePassword.innerHTML = '<i class="fas fa-eye"></i>';
            togglePassword.style.cursor = 'pointer';

            const passwordFields = document.querySelectorAll('input[type="password"]');
            passwordFields.forEach(field => {
                const parent = field.parentNode;
                if (parent.classList.contains('input-group')) {
                    const toggle = togglePassword.cloneNode(true);
                    parent.appendChild(toggle);

                    toggle.addEventListener('click', function() {
                        const icon = this.querySelector('i');
                        if (field.type === 'password') {
                            field.type = 'text';
                            icon.classList.remove('fa-eye');
                            icon.classList.add('fa-eye-slash');
                        } else {
                            field.type = 'password';
                            icon.classList.remove('fa-eye-slash');
                            icon.classList.add('fa-eye');
                        }
                    });
                }
            });
        });
    </script>
@stop
