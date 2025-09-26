<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bem-vindo | Guardião da Meta</title>

    <!-- Fonte e Tailwind -->
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700&display=swap" rel="stylesheet" />
    @vite('resources/css/app.css')

    <style>
        :root {
            --primary: #10B981;
            --primary-dark: #059669;
            --primary-light: #34D399;
            --secondary: #3B82F6;
            --accent: #8B5CF6;
            --dark: #1F2937;
            --light: #F9FAFB;
        }

        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background:
                linear-gradient(rgba(0, 0, 0, 0.85), rgba(0, 0, 0, 0.9)),
                url('{{ asset('assets/img/fundo_welcome.jpeg') }}') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            min-height: 100vh;
            color: var(--light);
        }

        .content {
            position: relative;
            z-index: 1;
        }

        .main-card {
            background: linear-gradient(135deg, rgba(25, 30, 35, 0.95) 0%, rgba(35, 40, 45, 0.95) 100%);
            border-radius: 20px;
            box-shadow:
                0 20px 40px rgba(0, 0, 0, 0.5),
                0 0 60px rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .main-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(52, 211, 153, 0.4), transparent);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            font-weight: 600;
            border-radius: 12px;
            padding: 14px 32px;
            transition: all 0.3s ease;
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
            border: none;
            position: relative;
            overflow: hidden;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::after {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);
            color: white;
            text-decoration: none;
        }

        .feature-list {
            list-style: none;
            padding: 0;
        }

        .feature-list li {
            position: relative;
            padding-left: 40px;
            margin-bottom: 16px;
            font-size: 1.1rem;
        }

        .feature-list li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: var(--primary-light);
            font-weight: bold;
            font-size: 1.4em;
            background: rgba(16, 185, 129, 0.1);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-container {
            position: relative;
            overflow: hidden;
            border-radius: 16px;
            padding: 20px;
            background: white !important;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.5);
            }
            70% {
                box-shadow: 0 0 0 12px rgba(16, 185, 129, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% { transform: translate(0, 0px); }
            50% { transform: translate(0, -10px); }
            100% { transform: translate(0, 0px); }
        }

        .title-gradient {
            background: linear-gradient(135deg, #FFFFFF 0%, #A7F3D0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            color: white; /* Fallback */
        }

        .highlight {
            color: var(--primary-light);
            font-weight: 600;
        }

        .cta-box {
            background: rgba(255, 255, 255, 0.05);
            padding: 16px 20px;
            border-radius: 12px;
            border-left: 4px solid var(--primary);
        }

        .instagram-link {
            color: #E5E7EB;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .instagram-link:hover {
            color: var(--primary-light);
            text-decoration: underline;
        }

        .status-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 12px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--primary-light);
            margin-right: 8px;
            animation: pulse 2s infinite;
        }

        .text-spacing {
            letter-spacing: 0.5px;
        }

        /* Responsividade melhorada */
        @media (max-width: 768px) {
            .main-card {
                padding: 24px;
                text-align: center;
            }

            .feature-list li {
                padding-left: 30px;
                font-size: 1rem;
            }

            .feature-list li:before {
                width: 24px;
                height: 24px;
                font-size: 1.2em;
            }
        }

        @media (max-width: 480px) {
            .main-card {
                padding: 16px;
            }

            h1 {
                font-size: 2rem;
            }

            .feature-list li {
                font-size: 0.9rem;
                padding-left: 25px;
            }

            .feature-list li:before {
                width: 20px;
                height: 20px;
                font-size: 1em;
            }

            .btn-primary {
                padding: 10px 20px;
                font-size: 0.9rem;
            }

            .logo-container {
                padding: 15px;
            }

            .logo-container img {
                width: 200px;
            }
        }
    </style>
</head>
<body class="antialiased">
    <div class="relative min-h-screen flex flex-col items-center justify-center content py-8 px-4">

        <!-- Caixa principal com texto, botões e logo -->
        <div class="main-card flex flex-col lg:flex-row items-center justify-between p-10 lg:p-14 max-w-6xl w-full gap-10 lg:gap-14 mb-8">

            <!-- Texto + Botões -->
            <div class="flex flex-col text-left max-w-xl">
                <h1 class="text-4xl lg:text-5xl font-bold mb-6 title-gradient">
                    Bem-vindo ao <span class="text-green-400">Guardião da Meta</span>
                </h1>

                <h2 class="text-lg lg:text-xl font-medium text-gray-200 mb-8 leading-relaxed">
                    A plataforma que <span class="highlight">conecta gestores, professores e alunos</span> de forma inteligente.
                </h2>

                <!-- Lista de vantagens -->
                <ul class="feature-list text-base lg:text-lg mb-8">
                    <li><span class="font-semibold">Gestão escolar simplificada</span> e intuitiva</li>
                    <li><span class="font-semibold">Controle completo</span> de notas e presença</li>
                    <li><span class="font-semibold">Comunicação direta</span> entre professores e alunos</li>
                    <li><span class="font-semibold">Relatórios detalhados</span> e personalizados</li>
                </ul>

                <!-- Botões de autenticação -->
                <div class="flex justify-center mb-8">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-primary pulse">
                                Acessar Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn-primary">
                                Fazer Login
                            </a>
                        @endauth
                    @endif
                </div>

                <!-- Call to action -->
                <div class="cta-box">
                    <p class="text-gray-200 flex items-center justify-center text-sm flex-wrap">
                        <span class="text-lg mr-2">🚀</span>
                        Entre agora e <span class="font-semibold text-green-300 ml-1">agilize os círculos de conversa da sua escola!</span>
                    </p>
                </div>

            </div>

            <!-- Logo -->
            <div class="flex flex-col items-center floating">
                <div class="logo-container mb-6">
                    <img src="{{ asset('assets/img/logo_eep.png') }}" alt="Logo EEEP"
                         class="w-48 lg:w-64 h-auto">
                </div>
                <span class="text-gray-300 text-center font-medium mb-2">Escola Estadual Profissionalizante</span>
                <div class="status-indicator">
                    <div class="status-dot"></div>
                    <span class="text-xs text-green-400">Sistema online</span>
                </div>
            </div>
        </div>

        <!-- Rodapé -->
        <div class="text-center text-gray-400 text-sm mb-2">
            &copy;{{ date('Y') }} Sistema Guardião da Meta — Desenvolvido por
            <a href="https://www.instagram.com/patricksousz_/" class="instagram-link">Anderson Patrick</a>
        </div>
    </div>
</body>
</html>
