<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - A Dieta que Funciona</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/adietaquefunciona.css') }}">
</head>
<body>
    <!-- Welcome Screen -->
    <div class="welcome-screen" id="welcomeScreen">
        <div class="welcome-content">
            <img src="{{ asset('images/logo_with_text.png') }}" alt="Nutri Diário Logo" class="welcome-logo">
            <h1 class="welcome-title">Bem-vindo ao <span style="white-space: nowrap;">Nutri Diário</span></h1>
            <p class="welcome-subtitle">Vamos criar seu plano personalizado de nutrição</p>
            <button class="start-btn" id="startBtn">VAMOS COMEÇAR</button>
        </div>
    </div>

    <!-- Celebration Overlay -->
    <div class="celebration-overlay" id="celebrationOverlay" style="display: none;">
        <div class="celebration-content">
            <div class="confetti"></div>
            <h2 class="celebration-title">🎉 Parabéns!</h2>
            <p class="celebration-text">Você está no caminho certo para transformar sua alimentação!</p>
            <button class="continue-btn" id="continueBtn">CONTINUAR</button>
        </div>
    </div>

    <div class="container" id="mainContainer" style="display: none;">
        <header class="header">
            <h1 class="title"><span style="white-space: nowrap;">Nutri Diário</span></h1>
            <p class="subtitle">Vamos conhecer melhor você para criar seu plano personalizado</p>
        </header>

        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
            <span class="progress-text" id="progressText">1 de 34</span>
        </div>

        <form id="nutritionForm" class="form">
            <!-- DISC Questions (12 perguntas) -->
            <div class="question-container" data-question="disc1" data-disc="true">
                <h2 class="question-title">Quando decide começar uma dieta, você:</h2>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="disc1" value="A">
                        <span class="option-text">Quer resultados rápidos e visíveis</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc1" value="B">
                        <span class="option-text">Fica animado com a novidade</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc1" value="C">
                        <span class="option-text">Prefere algo simples e duradouro</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc1" value="D">
                        <span class="option-text">Quer entender todos os detalhes antes</span>
                    </label>
                </div>
            </div>

            <div class="question-container" data-question="disc2" data-disc="true">
                <h2 class="question-title">Se sai da dieta, o que faz:</h2>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="disc2" value="A">
                        <span class="option-text">Volta firme no dia seguinte</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc2" value="B">
                        <span class="option-text">Acha que "tudo bem, depois recomeço"</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc2" value="C">
                        <span class="option-text">Fica frustrado, mas tenta manter a calma</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc2" value="D">
                        <span class="option-text">Analisa o que deu errado e ajusta o plano</span>
                    </label>
                </div>
            </div>

            <div class="question-container" data-question="disc3" data-disc="true">
                <h2 class="question-title">O que mais motiva você a manter a alimentação correta:</h2>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="disc3" value="A">
                        <span class="option-text">Bater metas e ver resultado rápido</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc3" value="B">
                        <span class="option-text">Ouvir elogios e se sentir bem socialmente</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc3" value="C">
                        <span class="option-text">Cuidar da saúde e manter rotina</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc3" value="D">
                        <span class="option-text">Saber que está fazendo tudo certo</span>
                    </label>
                </div>
            </div>

            <div class="question-container" data-question="disc4" data-disc="true">
                <h2 class="question-title">Quando o nutricionista muda o plano:</h2>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="disc4" value="A">
                        <span class="option-text">Aceita se achar que acelera o progresso</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc4" value="B">
                        <span class="option-text">Gosta da novidade</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc4" value="C">
                        <span class="option-text">Prefere mudanças pequenas</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc4" value="D">
                        <span class="option-text">Questiona e quer entender o motivo</span>
                    </label>
                </div>
            </div>

            <div class="question-container" data-question="disc5" data-disc="true">
                <h2 class="question-title">Seu comportamento em festas ou eventos:</h2>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="disc5" value="A">
                        <span class="option-text">Come com controle e volta ao plano</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc5" value="B">
                        <span class="option-text">Gosta de experimentar de tudo</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc5" value="C">
                        <span class="option-text">Evita exageros, mas participa</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc5" value="D">
                        <span class="option-text">Planeja antes o que pode comer</span>
                    </label>
                </div>
            </div>

            <div class="question-container" data-question="disc6" data-disc="true">
                <h2 class="question-title">Quando sente fome fora de hora:</h2>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="disc6" value="A">
                        <span class="option-text">Segura e espera o horário</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc6" value="B">
                        <span class="option-text">Belisca algo leve</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc6" value="C">
                        <span class="option-text">Busca alternativa dentro da dieta</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc6" value="D">
                        <span class="option-text">Reavalia horários e ajusta o plano</span>
                    </label>
                </div>
            </div>

            <div class="question-container" data-question="disc7" data-disc="true">
                <h2 class="question-title">Seu prato ideal:</h2>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="disc7" value="A">
                        <span class="option-text">Simples e funcional</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc7" value="B">
                        <span class="option-text">Colorido e cheio de sabores</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc7" value="C">
                        <span class="option-text">Caseiro e equilibrado</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc7" value="D">
                        <span class="option-text">Bem calculado e pesado corretamente</span>
                    </label>
                </div>
            </div>

            <div class="question-container" data-question="disc8" data-disc="true">
                <h2 class="question-title">Sua reação diante de uma restrição alimentar:</h2>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="disc8" value="A">
                        <span class="option-text">Cumpre sem reclamar</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc8" value="B">
                        <span class="option-text">Tenta driblar de vez em quando</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc8" value="C">
                        <span class="option-text">Se adapta aos poucos</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc8" value="D">
                        <span class="option-text">Quer saber a razão exata</span>
                    </label>
                </div>
            </div>

            <div class="question-container" data-question="disc9" data-disc="true">
                <h2 class="question-title">Como você lida com a rotina alimentar:</h2>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="disc9" value="A">
                        <span class="option-text">Gosta de metas claras e horários fixos</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc9" value="B">
                        <span class="option-text">Precisa de variedade e flexibilidade</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc9" value="C">
                        <span class="option-text">Mantém a disciplina com constância</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc9" value="D">
                        <span class="option-text">Prefere tudo organizado e medido</span>
                    </label>
                </div>
            </div>

            <div class="question-container" data-question="disc10" data-disc="true">
                <h2 class="question-title">Quando viaja ou muda de ambiente:</h2>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="disc10" value="A">
                        <span class="option-text">Cria uma nova estratégia rápida</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc10" value="B">
                        <span class="option-text">Se adapta com o que tem</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc10" value="C">
                        <span class="option-text">Tenta manter o máximo possível</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc10" value="D">
                        <span class="option-text">Leva tudo planejado</span>
                    </label>
                </div>
            </div>

            <div class="question-container" data-question="disc11" data-disc="true">
                <h2 class="question-title">Sobre acompanhamento nutricional:</h2>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="disc11" value="A">
                        <span class="option-text">Quer metas e comparativos de evolução</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc11" value="B">
                        <span class="option-text">Precisa de incentivo e contato próximo</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc11" value="C">
                        <span class="option-text">Gosta de constância e orientações simples</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc11" value="D">
                        <span class="option-text">Quer planilhas, medidas e relatórios técnicos</span>
                    </label>
                </div>
            </div>

            <div class="question-container" data-question="disc12" data-disc="true">
                <h2 class="question-title">O que mais atrapalha sua dieta:</h2>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="disc12" value="A">
                        <span class="option-text">Falta de resultado rápido</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc12" value="B">
                        <span class="option-text">Tédio ou falta de motivação</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc12" value="C">
                        <span class="option-text">Mudanças na rotina</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="disc12" value="D">
                        <span class="option-text">Falta de organização e dados claros</span>
                    </label>
                </div>
            </div>

            <!-- Question 0.8 - Dados de contato -->
            <div class="question-container" data-question="0.8">
                <h2 class="question-title">Para finalizar, me informe seus dados de contato:</h2>
                <div class="personal-info-group">
                    <div class="info-field">
                        <label for="contact_nome">Nome completo *</label>
                        <input type="text" id="contact_nome" name="contact_nome" required placeholder="Seu nome completo">
                    </div>
                    <div class="info-field">
                        <label for="contact_email">E-mail *</label>
                        <input type="email" id="contact_email" name="contact_email" required placeholder="seu@email.com">
                    </div>
                    <div class="info-field">
                        <label for="contact_whatsapp">WhatsApp *</label>
                        <input type="tel" id="contact_whatsapp" name="contact_whatsapp" required placeholder="(00) 00000-0000">
                    </div>
                    <div class="info-field">
                        <label for="contact_nascimento">Data de nascimento *</label>
                        <input type="date" id="contact_nascimento" name="contact_nascimento" max="2008-12-31" required>
                    </div>
                </div>
            </div>

            <!-- Question 1 -->
            <div class="question-container" data-question="1">
                <h2 class="question-title">Qual é o seu objetivo principal com a nutrição?</h2>
                <div class="options-grid">
                    <label class="option-card">
                        <input type="radio" name="q1" value="emagrecer">
                        <img src="{{ asset('images/forms/emagrecimento_saudavel.png') }}" alt="Emagrecimento saudável" class="option-image">
                        <span class="option-text">Emagrecer</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q1" value="ganhar_massa">
                        <img src="{{ asset('images/forms/ganho_de_massa_muscular.png') }}" alt="Ganho de massa muscular" class="option-image">
                        <span class="option-text">Ganhar massa magra</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q1" value="definicao">
                        <img src="{{ asset('images/forms/definicao_corporal.png') }}" alt="Definição corporal" class="option-image">
                        <span class="option-text">Definição corporal</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q1" value="saude">
                        <img src="{{ asset('images/forms/melhoria_da_saude_e_energia.png') }}" alt="Melhoria da saúde e energia" class="option-image">
                        <span class="option-text">Melhorar saúde/energia</span>
                    </label>
                </div>
                <div class="tip-container">
                    <button type="button" class="tip-button" onclick="toggleTip(1)">💡 Entenda</button>
                    <div class="tip-content" id="tip-1" style="display: none;">
                        <p>Objetivos claros funcionam como bússola. Quando você sabe para onde quer ir, fica mais fácil organizar o caminho e se manter motivado.</p>
                    </div>
                </div>
            </div>

            <!-- Question 2 -->
            <div class="question-container" data-question="2">
                <h2 class="question-title">Qual sua maior dificuldade em seguir uma dieta?</h2>
                <div class="options-grid">
                    <label class="option-card">
                        <input type="radio" name="q2" value="disciplina">
                        <img src="{{ asset('images/forms/01_falta_disciplina.png') }}" alt="Falta de disciplina" class="option-image">
                        <span class="option-text">Falta de disciplina</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q2" value="tempo">
                        <img src="{{ asset('images/forms/02_falta_tempo.png') }}" alt="Falta de tempo" class="option-image">
                        <span class="option-text">Falta de tempo</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q2" value="fome_ansiedade">
                        <img src="{{ asset('images/forms/03_fome_ansiedade.png') }}" alt="Fome e ansiedade" class="option-image">
                        <span class="option-text">Fome/ansiedade</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q2" value="organizacao">
                        <img src="{{ asset('images/forms/04_organizacao_refeicoes.png') }}" alt="Organização das refeições" class="option-image">
                        <span class="option-text">Organização das refeições</span>
                    </label>
                </div>
                <div class="feedback">
                    <p>A maioria das pessoas não desiste pela dieta em si, mas por não ter rotina estruturada. Organização e ajustes constantes tornam tudo mais leve.</p>
                </div>
            </div>

            <!-- Question 3 -->
            <div class="question-container" data-question="3">
                <h2 class="question-title">O que mais te motiva a mudar seus hábitos?</h2>
                <div class="options-grid">
                    <label class="option-card">
                        <input type="radio" name="q3" value="estetica">
                        <img src="{{ asset('images/forms/1 - estetica.png') }}" alt="Estética" class="option-image">
                        <span class="option-text">Estética</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q3" value="saude">
                        <img src="{{ asset('images/forms/2 - saúde.png') }}" alt="Saúde" class="option-image">
                        <span class="option-text">Saúde</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q3" value="energia">
                        <img src="{{ asset('images/forms/3 - energia e disposição.png') }}" alt="Energia e disposição" class="option-image">
                        <span class="option-text">Energia e disposição</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q3" value="autoestima">
                        <img src="{{ asset('images/forms/4 - autoestima.png') }}" alt="Autoestima" class="option-image">
                        <span class="option-text">Autoestima</span>
                    </label>
                </div>
                <div class="feedback">
                    <p>Quando a motivação é clara, até os dias difíceis ficam suportáveis. Relembrar seu porquê ajuda a manter consistência.</p>
                </div>
            </div>

            <!-- Question 4 -->
            <div class="question-container" data-question="4">
                <h2 class="question-title">Já seguiu algum plano alimentar antes?</h2>
                <div class="options-grid">
                    <label class="option-card">
                        <input type="radio" name="q4" value="sim_funcionou">
                        <img src="{{ asset('images/forms/1- funcionou bem.png') }}" alt="Funcionou bem" class="option-image">
                        <span class="option-text">Sim, funcionou bem</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q4" value="sim_nao_funcionou">
                        <img src="{{ asset('images/forms/2- não funcionou.png') }}" alt="Não funcionou" class="option-image">
                        <span class="option-text">Sim, mas não funcionou</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q4" value="pouco_tempo">
                        <img src="{{ asset('images/forms/3- pouco tempo.png') }}" alt="Pouco tempo" class="option-image">
                        <span class="option-text">Segui só por pouco tempo</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q4" value="nunca">
                        <img src="{{ asset('images/forms/4- nunca segui.png') }}" alt="Nunca segui" class="option-image">
                        <span class="option-text">Nunca segui nenhum</span>
                    </label>
                </div>
                <div class="feedback">
                    <p>A diferença não está apenas no plano, mas em manter constância. Ajustes pequenos e contínuos e o acompanhamento diário fazem mais efeito que mudanças radicais e aumentam a aderência ao processo.</p>
                </div>
            </div>

            <!-- Question 5 -->
            <div class="question-container" data-question="5">
                <h2 class="question-title">Qual é sua altura atual (em centímetros)?</h2>
                <div class="input-group height-input-group">
                    <input type="text" name="q5" placeholder="Ex: 170" class="text-input height-input">
                    <span class="input-suffix">cm</span>
                    <div class="input-hint">📏 Digite sua altura em centímetros (ex: 170)</div>
                </div>
            </div>

            <!-- Question 6 -->
            <div class="question-container" data-question="6">
                <h2 class="question-title">Qual é seu peso atual (em kg)?</h2>
                <div class="input-group weight-input-group">
                    <input type="text" name="q6" placeholder="Ex: 70.5" class="text-input weight-input">
                    <span class="input-suffix">kg</span>
                    <div class="input-hint">💡 Use ponto para decimais (ex: 70.5)</div>
                </div>
            </div>

            <!-- Question 7 -->
            <div class="question-container" data-question="7">
                <h2 class="question-title">Qual é o peso/meta que você quer atingir?</h2>
                <div class="input-group weight-input-group">
                    <input type="text" name="q7" placeholder="Ex: 65.0" class="text-input weight-input">
                    <span class="input-suffix">kg</span>
                    <div class="input-hint">💡 Use ponto para decimais (ex: 65.0)</div>
                </div>
                <div class="feedback">
                    <p>Ter um peso meta aumenta em até 30% as chances de sucesso. Quando a meta é clara, fica mais fácil manter constância.</p>
                </div>
            </div>

            <!-- Question 8 -->
            <div class="question-container" data-question="8">
                <h2 class="question-title">Como você descreveria sua rotina alimentar atual?</h2>
                <div class="options-grid">
                    <label class="option-card">
                        <input type="radio" name="q8" value="regrada">
                        <img src="{{ asset('images/forms/1- regrada organizada.png') }}" alt="Regrada e organizada" class="option-image">
                        <span class="option-text">Regrada e organizada</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q8" value="irregular">
                        <img src="{{ asset('images/forms/2- irregular.png') }}" alt="Irregular" class="option-image">
                        <span class="option-text">Irregular (pulo refeições)</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q8" value="fora_casa">
                        <img src="{{ asset('images/forms/3- fora de casa.png') }}" alt="Fora de casa" class="option-image">
                        <span class="option-text">Muitas refeições fora de casa</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q8" value="impulso">
                        <img src="{{ asset('images/forms/4- impulso.png') }}" alt="Impulso emocional" class="option-image">
                        <span class="option-text">Como por impulso/emocional</span>
                    </label>
                </div>
                <div class="feedback">
                    <p>Reconhecer seu padrão atual é o primeiro passo. A partir daí, organização e motivação podem transformar sua rotina alimentar.</p>
                </div>
            </div>

            <!-- Question 9 -->
            <div class="question-container" data-question="9">
                <h2 class="question-title">Quantas refeições por dia você costuma fazer?</h2>
                <div class="options-grid">
                    <label class="option-card">
                        <input type="radio" name="q9" value="2-3">
                        <img src="{{ asset('images/forms/1- duas.png') }}" alt="2 a 3 refeições" class="option-image">
                        <span class="option-text">2–3</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q9" value="4">
                        <img src="{{ asset('images/forms/2- quatro.png') }}" alt="4 refeições" class="option-image">
                        <span class="option-text">4</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q9" value="5">
                        <img src="{{ asset('images/forms/3- cinco.png') }}" alt="5 refeições" class="option-image">
                        <span class="option-text">5</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q9" value="6+">
                        <img src="{{ asset('images/forms/4- seis.png') }}" alt="6 ou mais refeições" class="option-image">
                        <span class="option-text">6 ou mais</span>
                    </label>
                </div>
                <div class="feedback">
                    <p>Pular refeições aumenta a chance de exagerar depois. Manter horários definidos organiza o corpo e reduz compulsão.</p>
                </div>
            </div>

            <!-- Question 10 -->
            <div class="question-container" data-question="10">
                <h2 class="question-title">Como costuma ser seu café da manhã?</h2>
                <div class="options-grid">
                    <label class="option-card">
                        <input type="radio" name="q10" value="completo">
                        <img src="{{ asset('images/forms/01_completo.png') }}" alt="Café da manhã completo" class="option-image">
                        <span class="option-text">Completo</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q10" value="simples">
                        <img src="{{ asset('images/forms/02_simples.png') }}" alt="Café da manhã simples" class="option-image">
                        <span class="option-text">Simples</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q10" value="leve">
                        <img src="{{ asset('images/forms/03_muito_leve.png') }}" alt="Café da manhã muito leve" class="option-image">
                        <span class="option-text">Muito leve</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q10" value="nao_tomo">
                        <img src="{{ asset('images/forms/04_nao_costumo.png') }}" alt="Não tomo café da manhã" class="option-image">
                        <span class="option-text">Não costumo tomar</span>
                    </label>
                </div>
                <div class="feedback">
                    <p>Pequenas escolhas logo cedo já mudam o ritmo do dia, podendo te deixar mais focado e energizado.</p>
                </div>
            </div>

            <!-- Question 11 -->
            <div class="question-container" data-question="11">
                <h2 class="question-title">E o jantar, costuma ser...?</h2>
                <div class="options-grid">
                    <label class="option-card">
                        <input type="radio" name="q11" value="pesado">
                        <img src="{{ asset('images/forms/01_pesado.png') }}" alt="Jantar pesado" class="option-image">
                        <span class="option-text">Pesado</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q11" value="moderado">
                        <img src="{{ asset('images/forms/02_moderado.png') }}" alt="Jantar moderado" class="option-image">
                        <span class="option-text">Moderado</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q11" value="leve">
                        <img src="{{ asset('images/forms/03_leve.png') }}" alt="Jantar leve" class="option-image">
                        <span class="option-text">Leve</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q11" value="quase_nao">
                        <img src="{{ asset('images/forms/04_quase_nao_janto.png') }}" alt="Quase não janto" class="option-image">
                        <span class="option-text">Quase não janto</span>
                    </label>
                </div>
                <div class="feedback">
                    <p>O importante não é o horário, mas a qualidade e a quantidade. Ajustes nesse horário fazem grande diferença na disposição do dia seguinte.</p>
                </div>
            </div>

            <!-- Question 12 -->
            <div class="question-container" data-question="12">
                <h2 class="question-title">Você consome lanches ou fast-food?</h2>
                <div class="options-grid">
                    <label class="option-card">
                        <input type="radio" name="q12" value="diariamente">
                        <img src="{{ asset('images/forms/01_diariamente.png') }}" alt="Fast-food diariamente" class="option-image">
                        <span class="option-text">Diariamente</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q12" value="3-4x">
                        <img src="{{ asset('images/forms/02_tres_quatro_vezes.png') }}" alt="Fast-food 3-4x por semana" class="option-image">
                        <span class="option-text">3–4x por semana</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q12" value="1-2x">
                        <img src="{{ asset('images/forms/03_uma_duas_vezes.png') }}" alt="Fast-food 1-2x por semana" class="option-image">
                        <span class="option-text">1–2x por semana</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q12" value="raramente">
                        <img src="{{ asset('images/forms/04_raramente_nunca.png') }}" alt="Fast-food raramente ou nunca" class="option-image">
                        <span class="option-text">Raramente/nunca</span>
                    </label>
                </div>
                <div class="feedback">
                    <p>O problema não é comer algo diferente, mas a frequência. Com escolhas organizadas, dá para equilibrar prazer e resultado.</p>
                </div>
            </div>

            <!-- Question 13 -->
            <div class="question-container" data-question="13">
                <h2 class="question-title">Como é sua relação com doces?</h2>
                <div class="options-grid">
                    <label class="option-card">
                        <input type="radio" name="q13" value="todos_dias">
                        <img src="{{ asset('images/forms/01_todos_os_dias.png') }}" alt="Como doces todos os dias" class="option-image">
                        <span class="option-text">Como todos os dias</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q13" value="algumas_vezes">
                        <img src="{{ asset('images/forms/02_algumas_vezes_semana.png') }}" alt="Como doces algumas vezes na semana" class="option-image">
                        <span class="option-text">Como algumas vezes na semana</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q13" value="raramente">
                        <img src="{{ asset('images/forms/03_raramente.png') }}" alt="Como doces raramente" class="option-image">
                        <span class="option-text">Como raramente</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q13" value="nao_gosto">
                        <img src="{{ asset('images/forms/04_nao_gosto.png') }}" alt="Não gosto de doces" class="option-image">
                        <span class="option-text">Não gosto de doces</span>
                    </label>
                </div>
                <div class="feedback">
                    <p>O desejo por doces muitas vezes vem da falta de rotina alimentar. Ter organização nas refeições reduz essa vontade naturalmente.</p>
                </div>
            </div>

            <!-- Question 14 -->
            <div class="question-container" data-question="14">
                <h2 class="question-title">Quais desses alimentos você NÃO gosta ou prefere NÃO consumir?</h2>
                <div class="checkbox-groups">
                    <div class="checkbox-group">
                        <h3 class="group-title">Proteínas</h3>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="frango">
                            <span class="checkbox-text">Frango</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="peixe">
                            <span class="checkbox-text">Peixe</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="atum">
                            <span class="checkbox-text">Atum</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="carne_bovina">
                            <span class="checkbox-text">Carne bovina</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="carne_suina">
                            <span class="checkbox-text">Carne suína</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="ovo">
                            <span class="checkbox-text">Ovo</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="whey">
                            <span class="checkbox-text">Whey</span>
                        </label>
                    </div>
                    
                    <div class="checkbox-group">
                        <h3 class="group-title">Carboidratos</h3>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="arroz_branco">
                            <span class="checkbox-text">Arroz branco</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="arroz_integral">
                            <span class="checkbox-text">Arroz integral</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="macarrao">
                            <span class="checkbox-text">Macarrão</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="pao_frances">
                            <span class="checkbox-text">Pão francês</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="pao_forma">
                            <span class="checkbox-text">Pão de forma</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="tapioca">
                            <span class="checkbox-text">Tapioca</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="aveia">
                            <span class="checkbox-text">Aveia</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="pipoca">
                            <span class="checkbox-text">Pipoca</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="rap10">
                            <span class="checkbox-text">Rap 10</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="bolacha_arroz">
                            <span class="checkbox-text">Bolacha de arroz</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="feijao">
                            <span class="checkbox-text">Feijão</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="grao_bico">
                            <span class="checkbox-text">Grão-de-bico</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="batata">
                            <span class="checkbox-text">Batata</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="batata_doce">
                            <span class="checkbox-text">Batata-doce</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="mandioca">
                            <span class="checkbox-text">Mandioca</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="mandioquinha">
                            <span class="checkbox-text">Mandioquinha</span>
                        </label>
                    </div>

                    <div class="checkbox-group">
                        <h3 class="group-title">Vegetais</h3>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="chuchu">
                            <span class="checkbox-text">Chuchu</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="abobrinha">
                            <span class="checkbox-text">Abobrinha</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="brocolis">
                            <span class="checkbox-text">Brócolis</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="couve_flor">
                            <span class="checkbox-text">Couve-flor</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="cenoura">
                            <span class="checkbox-text">Cenoura</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="cebola">
                            <span class="checkbox-text">Cebola</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="tomate">
                            <span class="checkbox-text">Tomate</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="pepino">
                            <span class="checkbox-text">Pepino</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="folhas_cruas">
                            <span class="checkbox-text">Folhas cruas (salada)</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="folhas_cozidas">
                            <span class="checkbox-text">Folhas cozidas (couve, repolho)</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="abobora">
                            <span class="checkbox-text">Abóbora</span>
                        </label>
                    </div>

                    <div class="checkbox-group">
                        <h3 class="group-title">Laticínios</h3>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="leite">
                            <span class="checkbox-text">Leite</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="queijo">
                            <span class="checkbox-text">Queijo</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="cottage">
                            <span class="checkbox-text">Cottage</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="iogurte">
                            <span class="checkbox-text">Iogurte</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="requeijao">
                            <span class="checkbox-text">Requeijão</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="ricota">
                            <span class="checkbox-text">Ricota</span>
                        </label>
                    </div>

                    <div class="checkbox-group">
                        <h3 class="group-title">Outros</h3>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="maionese">
                            <span class="checkbox-text">Maionese</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q14" value="mel">
                            <span class="checkbox-text">Mel</span>
                        </label>
                    </div>
                </div>
                <div class="feedback">
                    <p>Respeitar preferências pessoais aumenta a adesão. O que importa não é comer de tudo, mas manter constância no que funciona para você.</p>
                </div>
            </div>

            <!-- Question 16 -->
            <div class="question-container" data-question="16">
                <h2 class="question-title">Tem alguma restrição alimentar?</h2>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="q16" value="nenhuma">
                        <span class="option-text">Nenhuma</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q16" value="lactose">
                        <span class="option-text">Intolerância à lactose</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q16" value="gluten">
                        <span class="option-text">Intolerância ao glúten</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q16" value="vegetariano">
                        <span class="option-text">Vegetarianismo/veganismo</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q16" value="outros">
                        <span class="option-text">Outros (alergias, etc)</span>
                    </label>
                </div>
                <div class="input-group conditional" id="q16_outros" style="display: none;">
                    <textarea name="q16_outros_text" placeholder="Descreva suas restrições alimentares..." class="text-area"></textarea>
                </div>
                <div class="feedback">
                    <p>Respeitar limites do corpo é parte da organização. Quando o plano se adapta, a chance de manter é muito maior.</p>
                </div>
            </div>

            <!-- Question 17 -->
            <div class="question-container" data-question="17">
                <h2 class="question-title">Você pede delivery quantas vezes na semana?</h2>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="q17" value="nunca">
                        <span class="option-text">Nunca/raramente</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q17" value="1-2x">
                        <span class="option-text">1–2 vezes</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q17" value="3-4x">
                        <span class="option-text">3–4 vezes</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q17" value="5+">
                        <span class="option-text">5 ou mais vezes</span>
                    </label>
                </div>
                <div class="feedback">
                    <p>O delivery faz parte da rotina de muita gente, mas pode atrapalhar os resultados. Com organização e acompanhamento diário é possível planejar opções melhores.</p>
                </div>
            </div>

            <!-- Question 18 -->
            <div class="question-container" data-question="18">
                <h2 class="question-title">Você costuma comer dentro ou fora de casa?</h2>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="q18" value="sempre_casa">
                        <span class="option-text">Sempre em casa</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q18" value="maioria_casa">
                        <span class="option-text">Maioria em casa</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q18" value="metade">
                        <span class="option-text">Metade/Metade</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q18" value="maioria_fora">
                        <span class="option-text">Maioria fora de casa</span>
                    </label>
                </div>
                <div class="feedback">
                    <p>Comer fora exige mais atenção às escolhas. Organização prévia ajuda a evitar exageros sem deixar de aproveitar momentos sociais.</p>
                </div>
            </div>

            <!-- Question 19 -->
            <div class="question-container" data-question="19">
                <h2 class="question-title">Você costuma cozinhar?</h2>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="q19" value="diariamente">
                        <span class="option-text">Sim, diariamente</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q19" value="algumas_vezes">
                        <span class="option-text">Sim, algumas vezes por semana</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q19" value="raramente">
                        <span class="option-text">Raramente</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q19" value="nunca">
                        <span class="option-text">Nunca</span>
                    </label>
                </div>
                <div class="feedback">
                    <p>Cozinhar dá mais controle sobre os ingredientes. Até quem cozinha pouco pode se organizar para ter opções melhores à mão.</p>
                </div>
            </div>

            <!-- Question 20 -->
            <div class="question-container" data-question="20">
                <h2 class="question-title">Você estaria disposto(a) a cozinhar?</h2>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="q20" value="adoro">
                        <span class="option-text">Sim, adoro cozinhar</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q20" value="pratico">
                        <span class="option-text">Sim, se for prático</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q20" value="talvez">
                        <span class="option-text">Talvez, depende do tempo</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q20" value="nao">
                        <span class="option-text">Não</span>
                    </label>
                </div>
                <div class="feedback">
                    <p>A disposição conta mais que a habilidade. Pequenos ajustes e receitas simples já mudam sua rotina alimentar.</p>
                </div>
            </div>

            <!-- Question 21 -->
            <div class="question-container" data-question="21">
                <h2 class="question-title">Quanto você costuma gastar por semana no mercado?</h2>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="q21" value="ate_100">
                        <span class="option-text">Até R$ 100</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q21" value="100_200">
                        <span class="option-text">R$ 100–200</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q21" value="200_400">
                        <span class="option-text">R$ 200–400</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q21" value="mais_400">
                        <span class="option-text">Mais de R$ 400</span>
                    </label>
                </div>
                <div class="feedback">
                    <p>Planejar compras evita desperdícios e escolhas impulsivas. Organização financeira também faz parte da constância alimentar.</p>
                </div>
            </div>

            <!-- Question 22 -->
            <div class="question-container" data-question="22">
                <h2 class="question-title">Como é sua rotina de hidratação?</h2>
                <div class="options-grid">
                    <label class="option-card">
                        <input type="radio" name="q22" value="mais_2l">
                        <img src="{{ asset('images/forms/01_mais_dois_litros.png') }}" alt="Mais de 2 litros por dia" class="option-image">
                        <span class="option-text">Bebo +2 litros/dia</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q22" value="1_2l">
                        <img src="{{ asset('images/forms/02_um_dois_litros.png') }}" alt="1 a 2 litros por dia" class="option-image">
                        <span class="option-text">1–2 litros/dia</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q22" value="menos_1l">
                        <img src="{{ asset('images/forms/03_menos_um_litro.png') }}" alt="Menos de 1 litro por dia" class="option-image">
                        <span class="option-text">Menos de 1 litro/dia</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q22" value="quase_nao">
                        <img src="{{ asset('images/forms/04_quase_nao_bebe.png') }}" alt="Quase não bebo água" class="option-image">
                        <span class="option-text">Quase não bebo</span>
                    </label>
                </div>
                <div class="feedback">
                    <p>Mais de 60% das pessoas não chegam a beber 2L de água por dia. Com rotina organizada, esse hábito se torna indispensável para energia e foco.</p>
                </div>
            </div>

            <!-- Question 23 -->
            <div class="question-container" data-question="23">
                <h2 class="question-title">Qual sua relação com bebidas alcoólicas?</h2>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="q23" value="nao_consumo">
                        <span class="option-text">Não consumo</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q23" value="socialmente">
                        <span class="option-text">Socialmente</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q23" value="frequentemente">
                        <span class="option-text">Frequentemente</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q23" value="exagero">
                        <span class="option-text">Exagero regularmente</span>
                    </label>
                </div>
                <div class="feedback">
                    <p>O álcool pode atrapalhar sono e desempenho físico. Ter clareza e moderação ajuda a manter equilíbrio sem abrir mão da vida social.</p>
                </div>
            </div>

            <!-- Question 24 -->
            <div class="question-container" data-question="24">
                <h2 class="question-title">Como está seu sono atualmente?</h2>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="q24" value="regular">
                        <span class="option-text">Regular (7–8h/dia)</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q24" value="pouco">
                        <span class="option-text">Pouco (5–6h/dia)</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q24" value="muito_pouco">
                        <span class="option-text">Muito pouco (<5h)</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q24" value="irregular">
                        <span class="option-text">Irregular</span>
                    </label>
                </div>
                <div class="feedback">
                    <p>Dormir pouco impacta hormônios da fome e da saciedade. Manter horários organizados melhora até suas escolhas alimentares.</p>
                </div>
            </div>

            <!-- Question 25 -->
            <div class="question-container" data-question="25">
                <h2 class="question-title">Qual horário você dorme?</h2>
                <div class="input-group">
                    <input type="time" name="q25" class="time-input">
                </div>
            </div>

            <!-- Question 26 -->
            <div class="question-container" data-question="26">
                <h2 class="question-title">Qual horário você acorda?</h2>
                <div class="input-group">
                    <input type="time" name="q26" class="time-input">
                </div>
            </div>

            <!-- Question 27 -->
            <div class="question-container" data-question="27">
                <h2 class="question-title">Qual é o horário de início e término dos seus principais compromissos (trabalho, estudo, etc.)?</h2>
                <div class="input-group">
                    <textarea name="q27" placeholder="Ex: Trabalho das 8h às 17h, faculdade das 19h às 22h..." class="text-area"></textarea>
                </div>
            </div>

            <!-- Question 28 -->
            <div class="question-container" data-question="28">
                <h2 class="question-title">No seu trabalho ou estudo, é possível levar lanches? Há lugar para guardar ou aquecer alimentos (geladeira, micro-ondas etc.)?</h2>
                <div class="input-group">
                    <textarea name="q28" placeholder="Descreva as facilidades disponíveis no seu local de trabalho/estudo..." class="text-area"></textarea>
                </div>
            </div>

            <!-- Question 29 -->
            <div class="question-container" data-question="29">
                <h2 class="question-title">Você tem alguma condição de saúde ou usa medicação contínua? (Pode marcar mais de uma opção)</h2>
                <div class="checkbox-groups">
                    <div class="checkbox-group">
                        <label class="checkbox-option">
                            <input type="checkbox" name="q29" value="nao">
                            <span class="checkbox-text">Não</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q29" value="pressao_colesterol">
                            <span class="checkbox-text">Sim, pressão/colesterol</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q29" value="diabetes">
                            <span class="checkbox-text">Sim, diabetes</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q29" value="anticoncepcional">
                            <span class="checkbox-text">Sim, anticoncepcional</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q29" value="tireoidismo">
                            <span class="checkbox-text">Sim, hipotireoidismo/hipertireoidismo</span>
                        </label>
                        <label class="checkbox-option">
                            <input type="checkbox" name="q29" value="outras" id="q29_outras">
                            <span class="checkbox-text">Outras</span>
                        </label>
                    </div>
                    <div class="other-text-container" id="q29_outras_container" style="display: none;">
                        <label for="q29_outras_texto">Especifique qual(is):</label>
                        <input type="text" name="q29_outras_texto" id="q29_outras_texto" class="text-input" placeholder="Digite aqui...">
                    </div>
                </div>
                <div class="feedback">
                    <p>Entender seu histórico de saúde permite ajustes seguros. Informação organizada é base para acompanhamento de qualidade.</p>
                </div>
            </div>

            <!-- Question 30 -->
            <div class="question-container" data-question="30">
                <h2 class="question-title">Você pratica atividade física?</h2>
                <div class="options-grid">
                    <label class="option-card">
                        <input type="radio" name="q30" value="3_5x">
                        <img src="{{ asset('images/forms/01_tres_cinco_vezes.png') }}" alt="Atividade física 3-5x por semana" class="option-image">
                        <span class="option-text">Sim, 3–5x por semana</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q30" value="irregular">
                        <img src="{{ asset('images/forms/02_irregular.png') }}" alt="Atividade física irregular" class="option-image">
                        <span class="option-text">Sim, mas irregular</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q30" value="quero_comecar">
                        <img src="{{ asset('images/forms/03_quero_comecar.png') }}" alt="Quero começar atividade física" class="option-image">
                        <span class="option-text">Não, mas quero começar</span>
                    </label>
                    <label class="option-card">
                        <input type="radio" name="q30" value="nao_pratico">
                        <img src="{{ asset('images/forms/04_nao_pratico.png') }}" alt="Não pratico atividade física" class="option-image">
                        <span class="option-text">Não pratico</span>
                    </label>
                </div>
                <div class="feedback">
                    <p>O corpo responde melhor quando une alimentação e movimento. Até caminhadas regulares já fazem diferença.</p>
                </div>
            </div>

            <!-- Question 31 -->
            <div class="question-container conditional" data-question="31" id="q31_container" style="display: none;">
                <h2 class="question-title">Qual atividade física você pratica?</h2>
                <div class="input-group">
                    <textarea name="q31" placeholder="Descreva as atividades que você pratica..." class="text-area"></textarea>
                </div>
                <div class="feedback">
                    <p>Cada exercício pede energia diferente. Saber sua atividade ajuda a ajustar a alimentação para desempenho e recuperação.</p>
                </div>
            </div>

            <!-- Question 32 -->
            <div class="question-container conditional" data-question="32" id="q32_container" style="display: none;">
                <h2 class="question-title">Qual é o horário que você costuma treinar?</h2>
                <div class="input-group">
                    <textarea name="q32" placeholder="Ex: Segunda, quarta e sexta das 18h às 19h..." class="text-area"></textarea>
                </div>
            </div>

            <!-- Question 33 -->
            <div class="question-container" data-question="33">
                <h2 class="question-title">Como prefere acompanhar sua evolução?</h2>
                <div class="options">
                    <label class="option">
                        <input type="radio" name="q33" value="peso">
                        <span class="option-text">Peso na balança</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q33" value="fotos">
                        <span class="option-text">Fotos de progresso</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q33" value="medidas">
                        <span class="option-text">Medidas corporais</span>
                    </label>
                    <label class="option">
                        <input type="radio" name="q33" value="todos">
                        <span class="option-text">Todos os anteriores</span>
                    </label>
                </div>
                <div class="feedback">
                    <p>Diferentes formas de medir progresso evitam frustração. Quando o acompanhamento é variado, fica mais fácil manter a motivação.</p>
                </div>
            </div>

            <!-- Question 34 -->
            <div class="question-container" data-question="34">
                <h2 class="question-title">Para finalizar, me informe por favor:</h2>
                <div class="personal-info">
                    <div class="input-group">
                        <label class="input-label">Nome completo</label>
                        <input type="text" name="nome_completo" placeholder="Seu nome completo" class="text-input" required>
                    </div>
                    <div class="input-group">
                        <label class="input-label">Data de nascimento</label>
                        <input type="date" name="data_nascimento" class="date-input" max="2008-12-31" required>
                    </div>
                    <div class="input-group">
                        <label class="input-label">WhatsApp com DDD</label>
                        <input type="tel" name="whatsapp" placeholder="(11) 99999-9999" class="text-input" required>
                    </div>
                    <div class="input-group">
                        <label class="input-label">E-mail</label>
                        <input type="email" name="email" placeholder="seu@email.com" class="text-input" required>
                    </div>
                </div>
                <div class="feedback">
                    <p>Organização dos seus dados garante um acompanhamento mais próximo e personalizado.</p>
                </div>
            </div>

            <!-- Thank you message -->
            <div class="question-container" data-question="35" id="thankYouContainer" style="display: none;">
                <div class="thank-you">
                    <h2 class="thank-you-title">Muito obrigado por responder todas as perguntas!</h2>
                    <p class="thank-you-text">
                        Cada detalhe que você compartilhou é importante para montar um plano alimentar que realmente se encaixe na sua rotina e nos seus objetivos.
                    </p>
                    <p class="thank-you-text">
                        Em até 24 horas você receberá o seu plano completo e também conhecerá o nutricionista que vai caminhar com você nos próximos 30 dias. Ele(a) estará ao seu lado, todos os dias, para ajustar, motivar, acompanhar e ajudar a manter a constância.
                    </p>
                    <p class="thank-you-text">
                        Essa é só a primeira etapa da sua jornada. Estamos juntos para transformar pequenas escolhas em grandes resultados.
                    </p>
                </div>
            </div>

            <div class="navigation">
                <button type="button" id="prevBtn" class="nav-btn prev-btn" disabled>Anterior</button>
                <button type="button" id="nextBtn" class="nav-btn next-btn">Próximo</button>
                <button type="submit" id="submitBtn" class="nav-btn submit-btn" style="display: none;">Finalizar</button>
            </div>
        </form>

        <div class="loading-overlay" id="loadingOverlay" style="display: none;">
            <div class="loading-content">
                <div class="spinner"></div>
                <p>Enviando suas respostas...</p>
            </div>
        </div>

        <!-- Success Overlay -->
        <div class="success-overlay" id="successOverlay" style="display: none;">
            <div class="success-content">
                <div class="success-icon">✓</div>
                <h2 class="success-title">Muito obrigado pelas suas respostas!</h2>
                <p class="success-message">
                    Recebemos todas as suas informações com sucesso.<br>
                    <strong>Em breve entraremos em contato com você pelo WhatsApp!</strong>
                </p>
                <div class="whatsapp-icon">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" fill="#25D366"/>
                    </svg>
                </div>
                <button class="success-btn" onclick="window.location.href='/'">Voltar ao Início</button>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/adietaquefunciona.js') }}?v={{ time() }}"></script>
</body>
</html>
