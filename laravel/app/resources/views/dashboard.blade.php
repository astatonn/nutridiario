<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        .text-nutrigreen { color: #4CAF50; }
        .bg-nutrigreen { background-color: #4CAF50; }
        .hover\:bg-nutrigreen-dark:hover { background-color: #2E7D32; }

        .btn-glow {
            box-shadow: 0 0 20px rgba(76, 175, 80, 0.5);
            transition: all 0.3s ease;
        }

        .btn-glow:hover {
            box-shadow: 0 0 30px rgba(76, 175, 80, 0.8), 0 0 40px rgba(76, 175, 80, 0.6);
            transform: translateY(-2px);
        }

        .grid-bg {
            background-image:
                linear-gradient(rgba(76, 175, 80, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(76, 175, 80, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        .fade-in {
            animation: fade-in 0.5s ease-in;
        }

        @keyframes fade-in {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body class="bg-gray-900 grid-bg min-h-screen">
    <!-- Navigation Bar -->
    <nav class="bg-gray-800 border-b border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <img src="{{ asset('images/logo_with_text.png') }}" alt="Logo" class="h-10">
                    <span class="ml-4 text-white font-semibold text-lg">Dashboard</span>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-300">{{ Auth::user()->name }}</span>
                    @if(Auth::user()->isAdmin())
                        <span class="px-3 py-1 bg-nutrigreen text-white text-xs font-bold rounded-full">ADMIN</span>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-300 hover:text-white transition">
                            Sair
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="py-12 px-4">
        <div class="max-w-7xl mx-auto">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-500 bg-opacity-20 border border-green-500 text-green-400 px-4 py-3 rounded-lg mb-6 fade-in">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-500 bg-opacity-20 border border-red-500 text-red-400 px-4 py-3 rounded-lg mb-6 fade-in">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-500 bg-opacity-20 border border-red-500 text-red-400 px-4 py-3 rounded-lg mb-6 fade-in">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Welcome Section -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Bem-vindo, {{ Auth::user()->name }}!</h1>
                <p class="text-gray-400">Gerencie sua conta e acesse os recursos do sistema</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <h3 class="text-gray-400 text-sm font-semibold mb-1">TIPO DE CONTA</h3>
                    <p class="text-white text-2xl font-bold">{{ Auth::user()->isAdmin() ? 'Administrador' : 'Usuário' }}</p>
                </div>
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <h3 class="text-gray-400 text-sm font-semibold mb-1">E-MAIL</h3>
                    <p class="text-white text-xl font-semibold">{{ Auth::user()->email }}</p>
                </div>
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <h3 class="text-gray-400 text-sm font-semibold mb-1">MEMBRO DESDE</h3>
                    <p class="text-white text-xl font-semibold">{{ Auth::user()->created_at->format('d/m/Y') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- My Profile Section -->
                <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                    <h2 class="text-white text-xl font-bold mb-4">Meu Perfil</h2>

                    <div class="mb-6">
                        <h3 class="text-nutrigreen font-semibold mb-3">Informações da Conta</h3>
                        <div class="space-y-2 text-gray-300">
                            <p><span class="font-semibold">Nome:</span> {{ Auth::user()->name }}</p>
                            <p><span class="font-semibold">E-mail:</span> {{ Auth::user()->email }}</p>
                            @if(Auth::user()->telefone)
                                <p><span class="font-semibold">Telefone:</span> {{ Auth::user()->telefone }}</p>
                            @endif
                        </div>
                    </div>

                    <hr class="border-gray-700 mb-6">

                    <h3 class="text-nutrigreen font-semibold mb-3">Alterar Senha</h3>
                    <form method="POST" action="{{ route('user.change-password') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-gray-300 text-sm font-semibold mb-2">Senha Atual</label>
                            <input
                                type="password"
                                name="current_password"
                                required
                                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-nutrigreen focus:ring-2 focus:ring-nutrigreen focus:ring-opacity-50 transition"
                                placeholder="Digite sua senha atual"
                            >
                        </div>

                        <div>
                            <label class="block text-gray-300 text-sm font-semibold mb-2">Nova Senha</label>
                            <input
                                type="password"
                                name="new_password"
                                required
                                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-nutrigreen focus:ring-2 focus:ring-nutrigreen focus:ring-opacity-50 transition"
                                placeholder="Mínimo 8 caracteres"
                            >
                        </div>

                        <div>
                            <label class="block text-gray-300 text-sm font-semibold mb-2">Confirmar Nova Senha</label>
                            <input
                                type="password"
                                name="new_password_confirmation"
                                required
                                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-nutrigreen focus:ring-2 focus:ring-nutrigreen focus:ring-opacity-50 transition"
                                placeholder="Digite a senha novamente"
                            >
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-nutrigreen text-white font-bold py-3 px-4 rounded-lg hover:bg-nutrigreen-dark transition duration-300"
                        >
                            ALTERAR SENHA
                        </button>
                    </form>
                </div>

                <!-- Admin Panel / User Actions -->
                <div class="space-y-6">
                    @if(Auth::user()->isAdmin())
                        <!-- Admin Panel -->
                        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                            <h2 class="text-white text-xl font-bold mb-4">Painel do Administrador</h2>
                            <div class="space-y-3">
                                <a href="{{ route('admin.respostas') }}" class="block w-full bg-gray-700 hover:bg-gray-600 text-white font-semibold py-3 px-4 rounded-lg transition text-center">
                                    📊 Ver Respostas do Teste das Cores
                                </a>
                                <a href="{{ route('admin.users') }}" class="block w-full bg-nutrigreen hover:bg-nutrigreen-dark text-white font-semibold py-3 px-4 rounded-lg transition text-center btn-glow">
                                    👥 Gerenciar Usuários
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Available Tests -->
                    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                        <h2 class="text-white text-xl font-bold mb-4">Testes Disponíveis</h2>
                        <div class="space-y-3">
                            <a href="{{ route('teste-cores.index') }}" class="block w-full bg-gray-700 hover:bg-gray-600 text-white font-semibold py-3 px-4 rounded-lg transition text-center">
                                🎨 Teste das Cores
                            </a>
                            <a href="{{ route('planoalimentar') }}" class="block w-full bg-gray-700 hover:bg-gray-600 text-white font-semibold py-3 px-4 rounded-lg transition text-center">
                                🥗 Plano Alimentar
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                        <h2 class="text-white text-xl font-bold mb-4">Links Rápidos</h2>
                        <div class="space-y-3">
                            <a href="/" class="block text-nutrigreen hover:text-green-400 transition">
                                ← Voltar para o início
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
