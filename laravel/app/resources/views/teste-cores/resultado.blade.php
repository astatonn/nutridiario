<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado do Teste - Nutridiário</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }

        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .profile-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }

        .profile-card h2 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .profile-card p {
            font-size: 16px;
            line-height: 1.6;
            opacity: 0.95;
        }

        .color-distribution {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .color-distribution h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 20px;
        }

        .color-bar {
            margin-bottom: 15px;
        }

        .color-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
            color: #555;
        }

        .bar {
            height: 30px;
            border-radius: 8px;
            transition: width 0.5s ease;
            display: flex;
            align-items: center;
            padding-left: 10px;
            color: white;
            font-weight: 600;
        }

        .bar-vermelho {
            background: linear-gradient(90deg, #dc3545, #c82333);
        }

        .bar-amarelo {
            background: linear-gradient(90deg, #ffc107, #ff9800);
        }

        .bar-verde {
            background: linear-gradient(90deg, #28a745, #218838);
        }

        .bar-azul {
            background: linear-gradient(90deg, #007bff, #0056b3);
        }

        .contact-info {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .contact-info h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 18px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 4px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .info-value {
            font-size: 15px;
            color: #333;
        }

        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .container {
                padding: 25px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Resultado do Seu Teste</h1>

        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <div class="profile-card">
            <h2>{{ $perfil['nome'] }}</h2>
            <p>{{ $perfil['descricao'] }}</p>
        </div>

        <div class="color-distribution">
            <h3>Distribuição das Cores</h3>
            @php
                $maxCount = $cores->max();
                $colorNames = [
                    'vermelho' => 'Vermelho (Disciplinado)',
                    'amarelo' => 'Amarelo (Sociável)',
                    'verde' => 'Verde (Analítico)',
                    'azul' => 'Azul (Livre)'
                ];
            @endphp

            @foreach(['vermelho', 'amarelo', 'verde', 'azul'] as $cor)
                @php
                    $count = $cores->get($cor, 0);
                    $percentage = $maxCount > 0 ? ($count / 10) * 100 : 0;
                @endphp
                <div class="color-bar">
                    <div class="color-label">
                        <span>{{ $colorNames[$cor] }}</span>
                        <span>{{ $count }} resposta{{ $count != 1 ? 's' : '' }}</span>
                    </div>
                    <div class="bar bar-{{ $cor }}" style="width: {{ $percentage }}%">
                        {{ $percentage }}%
                    </div>
                </div>
            @endforeach
        </div>

        @php
            $firstResposta = $respostas->first();
        @endphp

        <div class="contact-info">
            <h3>Suas Informações</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Nome</span>
                    <span class="info-value">{{ $firstResposta->nome }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">E-mail</span>
                    <span class="info-value">{{ $firstResposta->email }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Telefone</span>
                    <span class="info-value">{{ $firstResposta->telefone }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Cidade/Estado</span>
                    <span class="info-value">{{ $firstResposta->cidade->nome }}/{{ $firstResposta->cidade->estado->uf }}</span>
                </div>
            </div>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('teste-cores.index') }}" class="btn">Fazer o Teste Novamente</a>
        </div>
    </div>
</body>
</html>
