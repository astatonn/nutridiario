<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ config('app.name') }}</title>
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

        .slide-in-bottom {
            animation: slide-in-bottom 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
        }

        @keyframes slide-in-bottom {
            0% { transform: translateY(100px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body class="bg-gray-900 grid-bg">
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full">
            <!-- Logo -->
            <div class="text-center mb-8 slide-in-bottom">
                <img src="{{ asset('images/logo_with_text.png') }}" alt="Logo" class="h-32 mx-auto mb-4">
                <h2 class="text-3xl font-bold text-white mb-2">Bem-vindo de volta!</h2>
                <p class="text-gray-400">Entre com suas credenciais</p>
            </div>

            <!-- Card de Login -->
            <div class="bg-gray-800 rounded-2xl shadow-2xl p-8 slide-in-bottom" style="animation-delay: 0.2s;">
                @if(session('status'))
                    <div class="bg-green-500 bg-opacity-20 border border-green-500 text-green-400 px-4 py-3 rounded-lg mb-6">
                        {{ session('status') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-500 bg-opacity-20 border border-red-500 text-red-400 px-4 py-3 rounded-lg mb-6">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-gray-300 text-sm font-semibold mb-2">E-mail</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-nutrigreen focus:ring-2 focus:ring-nutrigreen focus:ring-opacity-50 transition"
                            placeholder="seu@email.com"
                        >
                    </div>

                    <!-- Senha -->
                    <div class="mb-6">
                        <label for="password" class="block text-gray-300 text-sm font-semibold mb-2">Senha</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-nutrigreen focus:ring-2 focus:ring-nutrigreen focus:ring-opacity-50 transition"
                            placeholder="••••••••"
                        >
                    </div>

                    <!-- Botão de Login -->
                    <button
                        type="submit"
                        class="w-full bg-nutrigreen text-white font-bold py-3 px-4 rounded-lg hover:bg-nutrigreen-dark btn-glow transition duration-300"
                    >
                        ENTRAR
                    </button>
                </form>

                <!-- Links -->
                <div class="mt-6 text-center space-y-2">
                    <a href="{{ route('register') }}" class="block text-nutrigreen hover:text-green-400 text-sm transition">
                        Não tem uma conta? Registre-se
                    </a>
                    <a href="/" class="block text-gray-400 hover:text-gray-300 text-sm transition">
                        ← Voltar para o início
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
