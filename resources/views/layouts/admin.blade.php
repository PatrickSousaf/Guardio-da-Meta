<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo_eep.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white hidden md:block">
            <div class="p-4">
                <h2 class="text-lg font-semibold">Ferramentas Admin</h2>
            </div>
            <nav class="mt-4">
                <a href="{{ route('admin.cursos.index') }}" class="block py-2 px-4 hover:bg-gray-700">Gerenciar Cursos</a>
                <a href="{{ route('register') }}" class="block py-2 px-4 hover:bg-gray-700">Registrar Usuários</a>
                <a href="{{ route('profile.show') }}" class="block py-2 px-4 hover:bg-gray-700">Meu Perfil</a>
                <!-- Add more admin links as needed -->
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 w-full md:w-auto">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('modals')

    @livewireScripts

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
