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
<link rel="stylesheet" href="{{ asset('css/profile-edit.css') }}">
@stop

@section('js')
<script>
const userName = '{{ $user->name }}';
const userEmail = '{{ $user->email }}';
</script>
<script src="{{ asset('js/profile-edit.js') }}"></script>
<script>
initProfileEdit(userName, userEmail);
</script>
@stop
