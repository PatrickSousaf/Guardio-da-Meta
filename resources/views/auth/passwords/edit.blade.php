@extends('adminlte::page')

@section('title', 'Alterar Senha')

@section('content_header')
    <h1>Alterar Senha</h1>
@stop

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-key mr-2"></i>Alterar Senha
                    </h3>
                </div>
                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <!-- Senha Atual -->
                        <div class="form-group">
                            <label for="current_password">Senha Atual</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                       id="current_password" name="current_password" required
                                       placeholder="Digite sua senha atual">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="toggleCurrentPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            @error('current_password')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Nova Senha -->
                        <div class="form-group">
                            <label for="password">Nova Senha</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock-open"></i></span>
                                </div>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password" required
                                       placeholder="Digite sua nova senha">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            @error('password')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text text-muted">
                                A senha deve ter pelo menos 8 caracteres, incluindo uma letra maiúscula, uma minúscula, um número e um caractere especial.
                            </small>
                        </div>

                        <!-- Confirmar Nova Senha -->
                        <div class="form-group">
                            <label for="password_confirmation">Confirmar Nova Senha</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                </div>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                       id="password_confirmation" name="password_confirmation" required
                                       placeholder="Confirme sua nova senha">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            @error('password_confirmation')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <i class="icon fas fa-check"></i> {{ session('status') }}
                            </div>
                        @endif
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-6">
                                <a href="{{ route('profile.edit') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left mr-2"></i>Voltar ao Perfil
                                </a>
                            </div>
                            <div class="col-6 text-right">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i>Alterar Senha
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Dicas de Segurança -->
            <div class="card card-info mt-4">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-shield-alt mr-2"></i>Dicas de Segurança
                    </h3>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            Use uma senha forte com pelo menos 8 caracteres
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            Combine letras maiúsculas, minúsculas, números e símbolos
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            Evite usar datas de nascimento ou nomes de familiares
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success mr-2"></i>
                            Não reutilize senhas de outros serviços
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .input-group .btn-outline-secondary {
            border-left: none;
        }

        .card-primary {
            border-top: 3px solid #007bff;
        }

        .card-info {
            border-top: 3px solid #17a2b8;
        }

        .form-control:invalid {
            border-color: #dc3545;
        }
    </style>
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle visibility for passwords
            function togglePasswordVisibility(inputId, toggleId) {
                const input = document.getElementById(inputId);
                const toggle = document.getElementById(toggleId);
                const icon = toggle.querySelector('i');

                toggle.addEventListener('click', function() {
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                });
            }

            togglePasswordVisibility('current_password', 'toggleCurrentPassword');
            togglePasswordVisibility('password', 'togglePassword');
            togglePasswordVisibility('password_confirmation', 'toggleConfirmPassword');

            // Real-time password strength indicator
            const passwordInput = document.getElementById('password');
            const strengthIndicator = document.createElement('div');
            strengthIndicator.className = 'password-strength mt-2';
            strengthIndicator.innerHTML = '<small class="text-muted">Senha fraca</small>';
            passwordInput.parentNode.appendChild(strengthIndicator);

            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                let message = 'Senha fraca';
                let color = 'text-danger';

                if (password.length >= 8) strength++;
                if (/[a-z]/.test(password)) strength++;
                if (/[A-Z]/.test(password)) strength++;
                if (/[0-9]/.test(password)) strength++;
                if (/[^A-Za-z0-9]/.test(password)) strength++;

                switch (strength) {
                    case 1: case 2:
                        message = 'Senha fraca';
                        color = 'text-danger';
                        break;
                    case 3:
                        message = 'Senha razoável';
                        color = 'text-warning';
                        break;
                    case 4:
                        message = 'Senha boa';
                        color = 'text-info';
                        break;
                    case 5:
                        message = 'Senha forte';
                        color = 'text-success';
                        break;
                }

                strengthIndicator.innerHTML = `<small class="${color}">${message}</small>`;
            });

            // Confirm password match
            const confirmPassword = document.getElementById('password_confirmation');
            confirmPassword.addEventListener('input', function() {
                if (this.value !== passwordInput.value) {
                    this.setCustomValidity('As senhas não coincidem');
                    this.classList.add('is-invalid');
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('is-invalid');
                }
            });

            // Loading state on submit
            document.querySelector('form').addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Alterando...';
                submitBtn.disabled = true;
            });
        });
    </script>
@stop
