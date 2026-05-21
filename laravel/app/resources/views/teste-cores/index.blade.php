<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Teste das Cores</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/adietaquefunciona.css') }}">
</head>
<body>
    <!-- Welcome Screen -->
    <div class="welcome-screen" id="welcomeScreen">
        <div class="welcome-content">
            <img src="{{ asset('images/logo_with_text.png') }}" alt="Nutri Diário Logo" class="welcome-logo">
            <h1 class="welcome-title">Bem-vindo ao<br><span style="white-space: nowrap;">Nutri Diário</span></h1>
            <p class="welcome-subtitle">Descubra seu estilo de comportamento alimentar</p>
            <button class="start-btn" id="startBtn">VAMOS COMEÇAR</button>
        </div>
    </div>

    <div class="container" id="mainContainer" style="display: none;">
        <header class="header">
            <h1 class="title">Teste das Cores</h1>
            <p class="subtitle">Responda as perguntas abaixo</p>
        </header>

        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
            <span class="progress-text" id="progressText">1 de {{ count($perguntas) + 1 }}</span>
        </div>

        <form id="testeCoresForm" class="form">
            @csrf

            <!-- Perguntas do Teste -->
            @foreach($perguntas as $index => $pergunta)
            <div class="question-container {{ $index === 0 ? 'active' : '' }}" data-question="{{ $index }}" data-pergunta-texto="{{ $pergunta->texto }}">
                <h2 class="question-title">{{ $pergunta->texto }}</h2>
                <div class="options">
                    @foreach($pergunta->opcoes as $opcao)
                    <label class="option">
                        <input type="radio" name="respostas[{{ $index }}]" value="{{ $opcao->id }}" data-cor="{{ $opcao->cor }}" data-letra="{{ $opcao->letra }}" data-opcao-texto="{{ $opcao->texto }}" required>
                        <span class="option-text">{{ $opcao->letra }}) {{ $opcao->texto }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endforeach

            <!-- Informações de Contato (tudo em uma única tela) -->
            <div class="question-container" data-question="{{ count($perguntas) }}">
                <h2 class="question-title">Para finalizar, precisamos de suas informações de contato</h2>
                <p class="contact-subtitle">Preencha os campos abaixo para que possamos entrar em contato com você</p>

                <div class="personal-info-group">
                    <div class="info-field">
                        <label for="nome">Nome Completo *</label>
                        <input type="text" name="nome" id="nome" class="text-input" placeholder="Digite seu nome completo" required>
                    </div>

                    <div class="info-field">
                        <label for="email">E-mail *</label>
                        <input type="email" name="email" id="email" class="text-input" placeholder="seu@email.com" required>
                    </div>

                    <div class="info-field">
                        <label for="telefone">Telefone/WhatsApp *</label>
                        <input type="tel" name="telefone" id="telefone" class="text-input" placeholder="(00) 00000-0000" required>
                    </div>

                    <div class="info-field">
                        <label for="estado">Estado *</label>
                        <select name="estado_id" id="estado" class="text-input" required>
                            <option value="">Selecione seu estado</option>
                            @foreach($estados as $estado)
                                <option value="{{ $estado->id }}">{{ $estado->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="info-field">
                        <label for="cidade">Cidade *</label>
                        <select name="cidade_id" id="cidade" class="text-input" required disabled>
                            <option value="">Primeiro selecione um estado</option>
                        </select>
                    </div>
                </div>

                <div class="contact-note">
                    <p>📱 Entraremos em contato pelo WhatsApp para apresentar seu resultado personalizado!</p>
                </div>
            </div>

            <div class="navigation">
                <button type="button" id="prevBtn" class="nav-btn prev-btn" disabled>Anterior</button>
                <button type="button" id="nextBtn" class="nav-btn next-btn">Próximo</button>
                <button type="submit" id="submitBtn" class="nav-btn submit-btn" style="display: none;">Finalizar</button>
            </div>
        </form>

        <!-- Loading Overlay -->
        <div class="loading-overlay" id="loadingOverlay" style="display: none;">
            <div class="loading-content">
                <div class="loading-spinner"></div>
                <p>Enviando suas respostas...</p>
            </div>
        </div>

        <!-- Success Overlay -->
        <div class="success-overlay" id="successOverlay" style="display: none;">
            <div class="success-content">
                <div class="success-icon">✓</div>
                <h2 class="success-title">Teste Concluído com Sucesso!</h2>
                <p class="success-message">
                    Muito obrigado por completar o teste das cores!<br>
                    Recebemos todas as suas respostas.<br><br>
                    <strong>Entraremos em contato em breve pelo WhatsApp com seu resultado personalizado!</strong>
                </p>
                <div class="whatsapp-icon">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" fill="#25D366"/>
                    </svg>
                </div>
                <div class="success-note">
                    <p>Fique atento ao seu WhatsApp! Nossa equipe entrará em contato em breve para apresentar sua análise completa.</p>
                </div>
                <button class="success-btn" onclick="window.location.href='/'">Voltar ao Início</button>
            </div>
        </div>
    </div>

    <script>
        let currentQuestion = 0;
        const totalQuestions = {{ count($perguntas) + 1 }};
        const questions = document.querySelectorAll('.question-container');
        const progressFill = document.getElementById('progressFill');
        const progressText = document.getElementById('progressText');
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        const submitBtn = document.getElementById('submitBtn');
        const welcomeScreen = document.getElementById('welcomeScreen');
        const mainContainer = document.getElementById('mainContainer');
        const startBtn = document.getElementById('startBtn');

        // Start button
        startBtn.addEventListener('click', () => {
            welcomeScreen.style.display = 'none';
            mainContainer.style.display = 'block';
            updateProgress();
        });

        // Estado/Cidade selector
        document.getElementById('estado').addEventListener('change', function() {
            const estadoId = this.value;
            const cidadeSelect = document.getElementById('cidade');

            if (!estadoId) {
                cidadeSelect.disabled = true;
                cidadeSelect.innerHTML = '<option value="">Selecione um estado primeiro</option>';
                return;
            }

            cidadeSelect.disabled = true;
            cidadeSelect.innerHTML = '<option value="">Carregando...</option>';

            fetch(`/cidades/${estadoId}`)
                .then(response => response.json())
                .then(data => {
                    cidadeSelect.innerHTML = '<option value="">Selecione uma cidade</option>';
                    data.forEach(cidade => {
                        cidadeSelect.innerHTML += `<option value="${cidade.id}">${cidade.nome}</option>`;
                    });
                    cidadeSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    cidadeSelect.innerHTML = '<option value="">Erro ao carregar cidades</option>';
                });
        });

        // Next button
        nextBtn.addEventListener('click', () => {
            if (!validateCurrentQuestion()) {
                alert('Por favor, responda a questão atual antes de continuar.');
                return;
            }

            if (currentQuestion < totalQuestions - 1) {
                nextQuestion();
            }
        });

        // Back button
        prevBtn.addEventListener('click', () => {
            if (currentQuestion > 0) {
                previousQuestion();
            }
        });

        function nextQuestion() {
            questions[currentQuestion].classList.remove('active');
            currentQuestion++;
            questions[currentQuestion].classList.add('active');
            updateProgress();
            updateButtons();

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function previousQuestion() {
            questions[currentQuestion].classList.remove('active');
            currentQuestion--;
            questions[currentQuestion].classList.add('active');
            updateProgress();
            updateButtons();

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function updateProgress() {
            const progress = ((currentQuestion + 1) / totalQuestions) * 100;
            progressFill.style.width = progress + '%';
            progressText.textContent = `${currentQuestion + 1} de ${totalQuestions}`;
        }

        function updateButtons() {
            // Show/hide back button
            prevBtn.disabled = currentQuestion === 0;

            // Show submit button on last question
            if (currentQuestion === totalQuestions - 1) {
                nextBtn.style.display = 'none';
                submitBtn.style.display = 'inline-block';
            } else {
                nextBtn.style.display = 'inline-block';
                submitBtn.style.display = 'none';
            }
        }

        function validateCurrentQuestion() {
            const currentQuestionElement = questions[currentQuestion];
            const inputs = currentQuestionElement.querySelectorAll('input[required], select[required]');

            for (let input of inputs) {
                if (input.type === 'radio') {
                    const radioGroup = currentQuestionElement.querySelectorAll(`input[name="${input.name}"]`);
                    const isChecked = Array.from(radioGroup).some(radio => radio.checked);
                    if (!isChecked) return false;
                } else if (input.type === 'text' || input.type === 'email' || input.type === 'tel') {
                    if (!input.value.trim()) return false;
                } else if (input.tagName === 'SELECT') {
                    if (!input.value) return false;
                }
            }

            return true;
        }

        // Allow Enter key to advance on text inputs
        document.querySelectorAll('.text-input, .select-input').forEach(input => {
            input.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    if (validateCurrentQuestion() && currentQuestion < totalQuestions - 1) {
                        nextQuestion();
                    }
                }
            });
        });

        // Calculate DISC profile from answers
        function calculateDISCProfile(formData) {
            const respostas = [];
            const coresCount = { vermelho: 0, amarelo: 0, verde: 0, azul: 0 };

            // Collect all DISC answers with color information
            for (let i = 0; i < {{ count($perguntas) }}; i++) {
                const questionContainer = document.querySelector(`.question-container[data-question="${i}"]`);
                const selectedInput = document.querySelector(`input[name="respostas[${i}]"]:checked`);

                if (selectedInput && questionContainer) {
                    const opcaoId = selectedInput.value;
                    const cor = selectedInput.getAttribute('data-cor');
                    const letra = selectedInput.getAttribute('data-letra');
                    const opcaoTexto = selectedInput.getAttribute('data-opcao-texto');
                    const perguntaTexto = questionContainer.getAttribute('data-pergunta-texto');

                    respostas.push({
                        pergunta_numero: i + 1,
                        pergunta_texto: perguntaTexto,
                        opcao_id: opcaoId,
                        opcao_letra: letra,
                        opcao_texto: opcaoTexto,
                        cor: cor
                    });

                    // Count colors
                    if (coresCount[cor] !== undefined) {
                        coresCount[cor]++;
                    }
                }
            }

            // Sort colors by count (descending)
            const coresOrdenadas = Object.entries(coresCount)
                .sort((a, b) => b[1] - a[1])
                .map(entry => entry[0]);

            const corDominante = coresOrdenadas[0] || null;
            const segundaCorPredominante = coresOrdenadas[1] || null;

            return {
                respostas: respostas,
                cores_count: coresCount,
                cor_dominante: corDominante,
                segunda_cor_predominante: segundaCorPredominante,
                total_respostas: respostas.length
            };
        }

        // Handle form submission
        document.getElementById('testeCoresForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            // Show loading overlay
            const loadingOverlay = document.getElementById('loadingOverlay');
            loadingOverlay.style.display = 'flex';

            try {
                const formData = new FormData(e.target);

                // Calculate DISC profile
                const discProfile = calculateDISCProfile(formData);

                // Prepare data for N8N
                const n8nData = {
                    identificador: 'adietaquefunciona',
                    teste_disc: discProfile,
                    contato: {
                        nome: formData.get('nome'),
                        email: formData.get('email'),
                        telefone: formData.get('telefone'),
                        estado_id: formData.get('estado_id'),
                        cidade_id: formData.get('cidade_id')
                    },
                    data_envio: new Date().toISOString()
                };

                // Send to N8N webhook
                const n8nResponse = await fetch('https://n8n.nutridiario.com.br/webhook/dadosnutri', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(n8nData)
                });

                if (!n8nResponse.ok) {
                    console.error('Erro ao enviar para N8N:', await n8nResponse.text());
                }

                // Send to Laravel backend
                const laravelResponse = await fetch('{{ route("teste-cores.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });

                if (laravelResponse.ok || n8nResponse.ok) {
                    // Hide loading
                    loadingOverlay.style.display = 'none';

                    // Show success overlay
                    const successOverlay = document.getElementById('successOverlay');
                    successOverlay.style.display = 'flex';
                } else {
                    throw new Error('Erro ao enviar formulário');
                }
            } catch (error) {
                console.error('Erro:', error);
                loadingOverlay.style.display = 'none';
                alert('Ocorreu um erro ao enviar suas respostas. Por favor, tente novamente.');
            }
        });
    </script>
</body>
</html>
