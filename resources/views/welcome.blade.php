<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bem-vindo</title>

    <!-- Fonte e Tailwind -->
    <link href="https://fonts.bunny.net/css?family=poppins:400,600,700&display=swap" rel="stylesheet" />
    @vite('resources/css/app.css')

    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: url('{{ asset('assets/img/fundo_welcome.jpeg') }}') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }

        /* Overlay escuro */
        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: hsla(0, 0%, 0%, 0.7);
            z-index: 0;
        }

        .content {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body class="antialiased">
    <div class="relative min-h-screen flex flex-col items-center justify-center content">

        <!-- Caixa com texto, botões e logo -->
        <div class="flex flex-col lg:flex-row items-center justify-between bg-black/60 backdrop-blur-md rounded-2xl shadow-2xl p-12 max-w-6xl w-full gap-10">

            <!-- Texto + Botões -->
            <div class="flex flex-col text-left text-white max-w-xl">
                <h1 class="text-5xl font-extrabold mb-4 drop-shadow-lg">Bem-vindo ao Guardião da Meta</h1>
                <h2 class="text-xl font-medium text-white/80 mb-6">A plataforma que conecta gestores, professores e alunos.</h2>

                <!-- Lista de vantagens -->
                <ul class="space-y-3 text-lg mb-8">
                    <li class="flex items-center gap-2"><span class="text-emerald-400">✔</span> Gestão simples e intuitiva</li>
                    <li class="flex items-center gap-2"><span class="text-emerald-400">✔</span> Controle de notas e presença</li>
                    <li class="flex items-center gap-2"><span class="text-emerald-400">✔</span> Comunicação direta com alunos</li>
                </ul>

                <!-- Botões de autenticação -->
                <div class="flex flex-wrap gap-4 mb-6">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-md transform hover:scale-105 transition duration-300">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-md transform hover:scale-105 transition duration-300">
                                Login
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-md transform hover:scale-105 transition duration-300">
                                    Registra-se
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>

                <!-- Call to action -->
                <p class="text-sm text-white/70 mb-4">🚀 Entre agora no sistema e simplifique a gestão escolar!</p>
            </div>

            <!-- Logo -->
            <div class="flex flex-col items-center">
                <img src="{{ asset('assets/img/logo_eep.png') }}" alt="Logo EEEP" class="w-64 h-auto drop-shadow-2xl transform hover:scale-105 transition duration-300 mb-4">
                <span class="text-white/70 text-sm">Escola Estadual Profissionalizante</span>
            </div>
        </div>

        <!-- Rodapé -->
        <div class="mt-8 text-center text-white/70 text-sm">
            &copy; {{ date('Y') }} Sistema Escolar — Desenvolvido por Anderson Patrick
        </div>

        <!-- Oferta / Instagram abaixo do rodapé -->
        <div class="mt-2 text-center text-white/70 text-sm flex items-center justify-center gap-2">
            <a href="https://www.instagram.com/patricksousz_/" target="_blank" class="flex items-center gap-2 hover:text-emerald-400 transition">
                Gostou do site? Me chame no Insta para criar o seu!
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2.163c3.204 0 3.584.012 4.849.07 1.366.062 2.633.334 3.608 1.308.975.975 1.247 2.242 1.308 3.608.058 1.265.07 1.645.07 4.849s-.012 3.584-.07 4.849c-.062 1.366-.334 2.633-1.308 3.608-.975.975-2.242 1.247-3.608 1.308-1.265.058-1.645.07-4.849.07s-3.584-.012-4.849-.07c-1.366-.062-2.633-.334-3.608-1.308-.975-.975-1.247-2.242-1.308-3.608C2.175 15.584 2.163 15.204 2.163 12s.012-3.584.07-4.849c.062-1.366.334-2.633 1.308-3.608.975-.975 2.242-1.247 3.608-1.308C8.416 2.175 8.796 2.163 12 2.163zm0-2.163C8.741 0 8.332.014 7.052.072 5.78.13 4.633.405 3.678 1.36c-.955.955-1.23 2.102-1.288 3.374C2.014 5.668 2 6.077 2 9.337v5.326c0 3.26.014 3.669.072 4.949.058 1.272.333 2.419 1.288 3.374.955.955 2.102 1.23 3.374 1.288C8.332 23.986 8.741 24 12 24s3.668-.014 4.948-.072c1.272-.058 2.419-.333 3.374-1.288.955-.955 1.23-2.102 1.288-3.374.058-1.28.072-1.689.072-4.949V9.337c0-3.26-.014-3.669-.072-4.949-.058-1.272-.333-2.419-1.288-3.374-.955-.955-2.102-1.23-3.374-1.288C15.668.014 15.259 0 12 0z"/>
                    <path d="M12 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zm0 10.162a3.999 3.999 0 1 1 0-7.998 3.999 3.999 0 0 1 0 7.998z"/>
                    <circle cx="18.406" cy="5.594" r="1.44"/>
                </svg>
            </a>
        </div>
    </div>
</body>
</html>
