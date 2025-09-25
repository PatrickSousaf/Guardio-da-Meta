@extends('adminlte::page')

@section('content')
<br><br>
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <!-- Notificação de Sucesso -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fa-2x mr-3"></i>
                <div>
                    <h5 class="alert-heading mb-1">Sucesso!</h5>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <!-- Notificação de Erro -->
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-triangle fa-2x mr-3"></i>
                <div>
                    <h5 class="alert-heading mb-1">Erro!</h5>
                    <ul class="mb-0 pl-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-user-plus mr-2"></i> Novo Usuário
                </h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('register.store') }}">
                    @csrf

                    <div class="row">
                        <!-- Name -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}" required autofocus
                                       placeholder="Digite o nome completo">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Email Address -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email" value="{{ old('email') }}" required
                                       placeholder="exemplo@escola.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Role -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="role" class="form-label">Função</label>
                                <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                                    <option value="">Selecione a função</option>
                                    <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Professor</option>
                                    <option value="management" {{ old('role') == 'management' ? 'selected' : '' }}>Coordenação</option>
                                    <option value="director" {{ old('role') == 'director' ? 'selected' : '' }}>Diretor</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Password -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="form-label">Senha</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password" required
                                       placeholder="Mínimo 8 caracteres">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                                <input type="password" class="form-control"
                                       id="password_confirmation" name="password_confirmation" required
                                       placeholder="Digite novamente a senha">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times mr-1"></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-user-plus mr-2"></i> Cadastrar Usuário
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Botão Voltar ao Dashboard abaixo do formulário -->
        <div class="text-center mt-4">
            <a href="{{ route('dashboard') }}" class="btn btn-warning">
                <i class="fas fa-arrow-left mr-2"></i> Voltar ao Dashboard
            </a>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .card {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(0, 0, 0, 0.125);
        border-radius: 0.5rem;
    }

    .card-header {
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }

    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #212529;
        font-weight: 500;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        border-color: #d39e00;
        color: #212529;
    }

    .form-control {
        border-radius: 0.375rem;
    }

    .btn {
        border-radius: 0.375rem;
        font-weight: 500;
    }

    .text-center {
        text-align: center;
    }

    /* Estilos personalizados para as notificações */
    .alert {
        border-radius: 0.5rem;
        border: none;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert-danger {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    .alert .fa-check-circle {
        color: #28a745;
    }

    .alert .fa-exclamation-triangle {
        color: #dc3545;
    }

    .alert-heading {
        font-weight: 600;
        font-size: 1.1rem;
    }

    .close {
        color: #6c757d;
        opacity: 0.8;
    }

    .close:hover {
        color: #495057;
        opacity: 1;
    }
</style>
@stop

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validação de senha em tempo real
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');

    function validatePassword() {
        if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity('As senhas não coincidem');
        } else {
            confirmPassword.setCustomValidity('');
        }
    }

    password.addEventListener('input', validatePassword);
    confirmPassword.addEventListener('input', validatePassword);

    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>
@stop
