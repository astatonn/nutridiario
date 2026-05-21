<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Teste - Admin | Nutridiário</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .header {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .back-link {
            display: inline-block;
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .profile-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-card h2 {
            font-size: 22px;
            margin-bottom: 10px;
        }

        .profile-card p {
            font-size: 15px;
            line-height: 1.6;
            opacity: 0.95;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 18px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
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

        .answers-list {
            list-style: none;
        }

        .answer-item {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 12px;
            border-left: 4px solid #667eea;
        }

        .question-text {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .answer-text {
            color: #555;
            font-size: 14px;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 8px;
        }

        .badge-vermelho {
            background: #ffe5e5;
            color: #dc3545;
        }

        .badge-amarelo {
            background: #fff8e1;
            color: #ff9800;
        }

        .badge-verde {
            background: #e8f5e9;
            color: #28a745;
        }

        .badge-azul {
            background: #e3f2fd;
            color: #007bff;
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 0;
            }

            .card, .header, .profile-card {
                border-radius: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <a href="{{ route('admin.respostas') }}" class="back-link">← Voltar para lista de respostas</a>
            <h1>Detalhes do Teste</h1>
        </div>

        <div class="profile-card">
            <h2>{{ $perfil['nome'] }}</h2>
            <p>{{ $perfil['descricao'] }}</p>
        </div>

        @php
            $firstResposta = $respostas->first();
        @endphp

        <div class="card">
            <h3>Informações do Participante</h3>
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
                <div class="info-item">
                    <span class="info-label">Data do Teste</span>
                    <span class="info-value">{{ $firstResposta->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>

        <div class="card">
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

        <div class="card">
            <h3>Respostas Detalhadas</h3>
            <ul class="answers-list">
                @foreach($respostas as $resposta)
                    <li class="answer-item">
                        <div class="question-text">
                            {{ $resposta->opcao->pergunta->ordem }}. {{ $resposta->opcao->pergunta->texto }}
                        </div>
                        <div class="answer-text">
                            {{ $resposta->opcao->letra }}) {{ $resposta->opcao->texto }}
                            <span class="badge badge-{{ $resposta->opcao->cor }}">{{ ucfirst($resposta->opcao->cor) }}</span>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0;">Análise Comportamental (IA)</h3>
                <button id="generateAnalysisBtn" class="btn-generate" onclick="generateAnalysis()">
                    <span id="btnText">{{ $analise ? 'Gerar Nova Análise' : 'Gerar Análise' }}</span>
                    <span id="btnLoading" style="display: none;">Gerando...</span>
                </button>
            </div>

            <div id="analysisContent" style="{{ $analise ? '' : 'display: none;' }}">
                <div id="analysisText" style="background: #f8f9fa; padding: 25px; border-radius: 8px; border-left: 4px solid #667eea; line-height: 1.8; color: #2c3e50;">{{ $analise ? $analise->analise : '' }}</div>
            </div>

            <div id="analysisPlaceholder" style="{{ $analise ? 'display: none;' : '' }} text-align: center; color: #999; padding: 40px;">
                Clique no botão acima para gerar uma análise comportamental detalhada usando Inteligência Artificial.
            </div>
        </div>
    </div>

    <style>
        .btn-generate {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-generate:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-generate:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        #analysisText h1, #analysisText h2, #analysisText h3 {
            color: #667eea;
            margin-top: 20px;
            margin-bottom: 12px;
            font-weight: 700;
        }

        #analysisText h1 { font-size: 20px; }
        #analysisText h2 { font-size: 18px; }
        #analysisText h3 { font-size: 16px; }

        #analysisText p {
            margin-bottom: 15px;
            line-height: 1.8;
        }

        #analysisText ul, #analysisText ol {
            margin-left: 20px;
            margin-bottom: 15px;
        }

        #analysisText li {
            margin-bottom: 8px;
            line-height: 1.7;
        }

        #analysisText strong {
            color: #667eea;
            font-weight: 700;
        }

        #analysisText em {
            font-style: italic;
            color: #555;
        }
    </style>

    <script>
        function formatMarkdown(text) {
            // Convert markdown to HTML
            let html = text;

            // Headers
            html = html.replace(/^### (.+)$/gm, '<h3>$1</h3>');
            html = html.replace(/^## (.+)$/gm, '<h2>$1</h2>');
            html = html.replace(/^# (.+)$/gm, '<h1>$1</h1>');

            // Bold with **
            html = html.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');

            // Italic with *
            html = html.replace(/\*(.+?)\*/g, '<em>$1</em>');

            // Numbered lists - maintain proper structure
            html = html.replace(/^(\d+)\.\s+(.+)$/gm, function(match, num, content) {
                return '<div style="margin-bottom: 15px;"><strong style="color: #667eea; font-size: 16px;">' + num + '. ' + content.replace(/\*\*(.+?)\*\*/g, '$1') + '</strong></div>';
            });

            // Bullet points with proper indentation
            html = html.replace(/^   - (.+)$/gm, '<div style="margin-left: 25px; margin-bottom: 8px; padding-left: 15px; border-left: 2px solid #e0e0e0;">• $1</div>');
            html = html.replace(/^- (.+)$/gm, '<div style="margin-bottom: 8px;">• $1</div>');

            // Paragraphs
            html = html.split('\n\n').map(para => {
                if (!para.trim().startsWith('<')) {
                    return '<p>' + para + '</p>';
                }
                return para;
            }).join('\n');

            return html;
        }

        function generateAnalysis() {
            const btn = document.getElementById('generateAnalysisBtn');
            const btnText = document.getElementById('btnText');
            const btnLoading = document.getElementById('btnLoading');
            const content = document.getElementById('analysisContent');
            const placeholder = document.getElementById('analysisPlaceholder');
            const analysisText = document.getElementById('analysisText');

            btn.disabled = true;
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline';

            fetch('{{ route('admin.respostas.generate-analysis', $firstResposta->session_id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    analysisText.innerHTML = formatMarkdown(data.analise);
                    content.style.display = 'block';
                    placeholder.style.display = 'none';
                    btnText.textContent = 'Gerar Nova Análise';
                } else {
                    alert('Erro: ' + data.message);
                }
            })
            .catch(error => {
                alert('Erro ao gerar análise. Por favor, tente novamente.');
                console.error('Error:', error);
            })
            .finally(() => {
                btn.disabled = false;
                btnText.style.display = 'inline';
                btnLoading.style.display = 'none';
            });
        }

        // Format existing analysis on page load
        document.addEventListener('DOMContentLoaded', function() {
            const analysisText = document.getElementById('analysisText');
            if (analysisText && analysisText.textContent.trim()) {
                analysisText.innerHTML = formatMarkdown(analysisText.textContent);
            }
        });
    </script>
</body>
</html>
