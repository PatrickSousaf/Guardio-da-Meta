<x-guest-layout>
    <div class="mb-4">
        @auth
            @if(Auth::user()->role === 'director')
                <div class="text-center mb-6">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left mr-1"></i> Ir para o Dashboard
                    </a>
                </div>
            @endif
        @endauth
    </div>

    <div class="mb-4">
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
            <div class="flex items-center">
                <i class="fas fa-user-shield mr-2"></i>
                <div>
                    <strong class="block">Registro de Novos Usuários</strong>
                    <small class="text-blue-600">Apenas diretores podem registrar novos usuários</small>
                </div>
            </div>
        </div>
    </div>

    @if(!session('invite_validated'))
    <!-- Formulário de Validação do Código -->
    <form method="POST" action="{{ route('validate.invite') }}">
        @csrf

        <div class="mb-4">
            <x-input-label for="invite_code" :value="__('Código de Convite')" />
            <x-text-input id="invite_code" class="block mt-1 w-full" type="text" name="invite_code" required autofocus />
            <x-input-error :messages="$errors->get('invite_code')" class="mt-2" />
            <p class="text-xs text-gray-500 mt-1">Insira o código de convite fornecido</p>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="w-full justify-center">
                <i class="fas fa-check mr-2"></i> {{ __('Validar Código') }}
            </x-primary-button>
        </div>
    </form>

    @else
    <!-- Formulário de Registro -->
    <form method="POST" action="{{ route('register.store') }}">
        @csrf

        <input type="hidden" name="invite_code" value="{{ session('invite_code') }}">

        <!-- Name -->
        <div class="mb-4">
            <x-input-label for="name" :value="__('Nome Completo')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mb-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Role (definida pelo código) -->
        <div class="mb-4">
            <x-input-label for="role" :value="__('Função')" />
            <div class="bg-gray-100 p-3 rounded border border-gray-300">
                <div class="flex items-center justify-between">
                    <strong class="text-gray-700">{{ ucfirst(session('allowed_role')) }}</strong>
                    <i class="fas fa-user-tag text-gray-500"></i>
                </div>
                <input type="hidden" name="role" value="{{ session('allowed_role') }}">
            </div>
            <p class="text-xs text-gray-500 mt-1">Função definida pelo código de convite</p>
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" :value="__('Senha')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar Senha')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('dashboard') }}">
                {{ __('Cancelar') }}
            </a>

            <x-primary-button>
                <i class="fas fa-user-plus mr-2"></i> {{ __('Registrar Usuário') }}
            </x-primary-button>
        </div>
    </form>
    @endif

    <!-- Link para login se não estiver autenticado -->
    @guest
    <div class="mt-4 text-center">
        <p class="text-sm text-gray-600">
            Já tem uma conta?
            <a href="{{ route('login') }}" class="underline text-gray-900 hover:text-gray-500">
                {{ __('Fazer login') }}
            </a>
        </p>
    </div>
    @endguest
</x-guest-layout>
