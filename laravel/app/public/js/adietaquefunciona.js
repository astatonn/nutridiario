        class NutritionForm {
            constructor() {
                this.baseQuestions = 33; // Questions excluding q0, q0.5, and q15 (which doesn't exist)
                this.totalQuestions = 46; // 32 normal questions + 12 DISC + contact 0.8 (1) + thank you = 46
                this.formData = {};
                this.discAnswers = {}; // Store DISC answers
                this.questionOrder = []; // Will store the order of questions to show
                this.flowType = 'mixed'; // Always use mixed flow (no diet path)
                this.questionOrderIndex = 0; // Start at first question in order

                this.initializeElements();
                this.setupMixedQuestions(); // Setup mixed questions immediately
                this.currentQuestion = this.questionOrder[0]; // Set to first question in mixed order
                this.bindEvents();
                this.showCurrentQuestion(); // Show the first question
                this.updateProgress();
                this.updateNavigation();
            }
            
            initializeElements() {
                this.form = document.getElementById('nutritionForm');
                this.prevBtn = document.getElementById('prevBtn');
                this.nextBtn = document.getElementById('nextBtn');
                this.submitBtn = document.getElementById('submitBtn');
                this.progressFill = document.getElementById('progressFill');
                this.progressText = document.getElementById('progressText');
                this.loadingOverlay = document.getElementById('loadingOverlay');
                
                this.questions = document.querySelectorAll('.question-container');
            }
            
            bindEvents() {
                this.prevBtn.addEventListener('click', () => this.previousQuestion());
                this.nextBtn.addEventListener('click', () => this.nextQuestion());

                // Submit button now uses sendToWhatsApp to send data to Supabase + webhook
                this.submitBtn.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.sendToWhatsApp();
                });

                // Handle conditional questions
                const q16Radios = document.querySelectorAll('input[name="q16"]');
                q16Radios.forEach(radio => {
                    radio.addEventListener('change', () => this.handleQ16Change());
                });

                const q30Radios = document.querySelectorAll('input[name="q30"]');
                q30Radios.forEach(radio => {
                    radio.addEventListener('change', () => this.handleQ30Change());
                });

                // Remove auto-advance - navigation only by button click
                // this.form.addEventListener('change', (e) => {
                //     if (e.target.type === 'radio') {
                //         setTimeout(() => {
                //             if (this.currentQuestion < this.totalQuestions) {
                //                 this.nextQuestion();
                //             }
                //         }, 800);
                //     }
                // });

                // Phone number formatting
                const whatsappInput = document.querySelector('input[name="whatsapp"]');
                if (whatsappInput) {
                    whatsappInput.addEventListener('input', this.formatPhoneNumber);
                }

                // Phone number formatting for contact form
                const contactWhatsappInput = document.getElementById('contact_whatsapp');
                if (contactWhatsappInput) {
                    contactWhatsappInput.addEventListener('input', this.formatPhoneNumber);
                }

                // Weight and height validation
                this.setupSimpleInputValidation();
            }
            
            setupSimpleInputValidation() {
                // Simple validation for weight and height inputs
                const setupInputs = () => {
                    // Weight inputs (allow decimal)
                    const weightInputs = document.querySelectorAll('input[name="q6"], input[name="q7"]');
                    weightInputs.forEach(input => {
                        input.addEventListener('input', this.validateWeight.bind(this));
                    });
                    
                    // Height input (integers only)
                    const heightInputs = document.querySelectorAll('input[name="q5"]');
                    heightInputs.forEach(input => {
                        input.addEventListener('input', this.validateHeight.bind(this));
                    });
                };
                
                // Try to setup immediately
                setupInputs();
                
                // Also setup when questions change
                setTimeout(setupInputs, 1000);
            }
            
            validateWeight(e) {
                let value = e.target.value;
                
                // Allow only numbers and one dot
                value = value.replace(/[^0-9.]/g, '');
                
                // Allow only one dot
                const dotCount = (value.match(/\./g) || []).length;
                if (dotCount > 1) {
                    value = value.replace(/\.+$/, '');
                }
                
                // Limit to 2 decimal places
                if (value.includes('.')) {
                    const parts = value.split('.');
                    if (parts[1] && parts[1].length > 2) {
                        value = parts[0] + '.' + parts[1].substring(0, 2);
                    }
                }
                
                e.target.value = value;
            }
            
            validateHeight(e) {
                let value = e.target.value;
                
                // Allow only numbers (no decimals for height)
                value = value.replace(/[^0-9]/g, '');
                
                // Limit to reasonable height range (100-250 cm)
                if (value && parseInt(value) > 250) {
                    value = '250';
                }
                
                e.target.value = value;
            }

            setupMixedQuestions() {
                // Cria uma ordem fixa intercalando perguntas normais (1-33) com DISC (disc1-12)
                // Padrão: a cada 3 perguntas normais, insere 1 pergunta DISC
                // Isso mantém o engajamento sem complexidade desnecessária

                const normalQuestions = [];
                const discQuestions = ['disc1', 'disc2', 'disc3', 'disc4', 'disc5', 'disc6',
                                      'disc7', 'disc8', 'disc9', 'disc10', 'disc11', 'disc12'];

                // Lista de perguntas normais (1-33, excluindo a 15 que não existe no HTML)
                for (let i = 1; i <= 33; i++) {
                    if (i !== 15) { // Pula questão 15 que não existe no HTML
                        normalQuestions.push(i);
                    }
                }

                const combined = [];
                let normalIndex = 0;
                let discIndex = 0;

                // Intercala: 3 normais, 1 DISC, 3 normais, 1 DISC, etc.
                while (normalIndex < normalQuestions.length || discIndex < discQuestions.length) {
                    // Adiciona até 3 perguntas normais
                    for (let i = 0; i < 3 && normalIndex < normalQuestions.length; i++) {
                        combined.push(normalQuestions[normalIndex]);
                        normalIndex++;
                    }

                    // Adiciona 1 pergunta DISC
                    if (discIndex < discQuestions.length) {
                        combined.push(discQuestions[discIndex]);
                        discIndex++;
                    }
                }

                this.questionOrder = combined;
            }

            calculateDISCScore() {
                const scores = { A: 0, B: 0, C: 0, D: 0 };

                // Conta as respostas de cada letra
                for (let i = 1; i <= 12; i++) {
                    const answer = this.discAnswers[`disc${i}`];
                    if (answer && scores.hasOwnProperty(answer)) {
                        scores[answer]++;
                    }
                }

                // Encontra o perfil dominante e secundário
                const sortedScores = Object.entries(scores).sort((a, b) => b[1] - a[1]);
                const maxScore = sortedScores[0][1];

                // Verifica empates no perfil dominante
                const empates = sortedScores.filter(([_, score]) => score === maxScore).map(([letter]) => letter);

                const result = {
                    total_A: scores.A,
                    total_B: scores.B,
                    total_C: scores.C,
                    total_D: scores.D,
                    perfil_dominante: sortedScores[0][0],
                    perfil_secundario: sortedScores[1][0],
                    scores: scores
                };

                if (empates.length > 1) {
                    result.empates = empates;
                    result.tem_empate = true;
                } else {
                    result.tem_empate = false;
                }

                // Adiciona informações descritivas dos perfis
                const perfilInfo = {
                    'A': { cor: 'Vermelho', tipo: 'Dominante', foco: 'Resultado' },
                    'B': { cor: 'Amarelo', tipo: 'Influente', foco: 'Prazer e estímulo' },
                    'C': { cor: 'Verde', tipo: 'Estável', foco: 'Constância e rotina' },
                    'D': { cor: 'Azul', tipo: 'Cauteloso', foco: 'Controle e precisão' }
                };

                result.perfil_dominante_info = perfilInfo[result.perfil_dominante];
                result.perfil_secundario_info = perfilInfo[result.perfil_secundario];

                return result;
            }

            async sendToWhatsApp() {
                // Validate contact fields
                const nome = document.getElementById('contact_nome')?.value;
                const email = document.getElementById('contact_email')?.value;
                const whatsapp = document.getElementById('contact_whatsapp')?.value;
                const nascimento = document.getElementById('contact_nascimento')?.value;

                if (!nome || !email || !whatsapp || !nascimento) {
                    this.showValidationMessage('Por favor, preencha todos os campos obrigatórios.');
                    return;
                }

                try {
                    // Show loading
                    this.loadingOverlay.style.display = 'flex';

                    // Calcula pontuação DISC
                    const discScore = this.calculateDISCScore();

                    // Always use 'nova_dieta_completa' since we removed the initial question
                    const tipo = 'nova_dieta_completa';

                    // Prepare data to send
                    const dataToSend = {
                        tipo: tipo,
                        nome_completo: nome,
                        email: email,
                        whatsapp: whatsapp,
                        data_nascimento: nascimento,
                        data_envio: new Date().toISOString(),
                        respostas_formulario: this.formData,
                        respostas_disc: this.discAnswers,
                        pontuacao_disc: discScore,
                        fluxo: this.flowType
                    };

                    // Send to Laravel backend (Supabase)
                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        const laravelResponse = await fetch('/planoalimentar', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': csrfToken || ''
                            },
                            body: JSON.stringify(dataToSend)
                        });

                        if (!laravelResponse.ok) {
                            console.error('Erro ao salvar no backend:', await laravelResponse.text());
                        }
                    } catch (error) {
                        console.error('Erro ao enviar para backend:', error);
                        // Continue execution even if Laravel fails
                    }

                    // Send to N8N webhook
                    const response = await fetch('https://n8n.nutridiario.com.br/webhook/salvardados', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(dataToSend)
                    });

                    if (!response.ok) {
                        throw new Error('Erro ao enviar dados');
                    }

                    // Hide loading
                    this.loadingOverlay.style.display = 'none';

                    // Show success overlay
                    const successOverlay = document.getElementById('successOverlay');
                    if (successOverlay) {
                        successOverlay.style.display = 'flex';
                    }

                } catch (error) {
                    console.error('Erro ao enviar:', error);
                    this.loadingOverlay.style.display = 'none';
                    alert('Erro ao enviar os dados. Por favor, tente novamente.');
                }
            }

            handleQ16Change() {
                const selectedValue = document.querySelector('input[name="q16"]:checked')?.value;
                const otrosField = document.getElementById('q16_outros');

                if (selectedValue === 'outros') {
                    otrosField.style.display = 'block';
                    setTimeout(() => {
                        otrosField.style.opacity = '1';
                        otrosField.style.transform = 'translateY(0)';
                    }, 50);
                } else {
                    otrosField.style.opacity = '0';
                    otrosField.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        otrosField.style.display = 'none';
                    }, 300);
                }
            }
            
            handleQ30Change() {
                const selectedValue = document.querySelector('input[name="q30"]:checked')?.value;
                
                // Just update totalQuestions based on selection, don't show questions yet
                if (selectedValue === '3_5x' || selectedValue === 'irregular') {
                    this.totalQuestions = 34; // Include conditional questions (30 base + 2 conditional + 2 final)
                } else if (selectedValue === 'quero_comecar' || selectedValue === 'nao_pratico') {
                    this.totalQuestions = 32; // Skip conditional questions (30 base + 2 final)
                }
                
                // Update progress immediately
                this.updateProgress();
            }
            
            formatPhoneNumber(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length >= 11) {
                    value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                } else if (value.length >= 7) {
                    value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
                } else if (value.length >= 3) {
                    value = value.replace(/(\d{2})(\d{0,5})/, '($1) $2');
                }
                e.target.value = value;
            }
            
            
            getCurrentQuestionElement() {
                return document.querySelector(`[data-question="${this.currentQuestion}"]`);
            }
            
            isCurrentQuestionValid() {
                const currentElement = this.getCurrentQuestionElement();
                if (!currentElement) {
                    return false;
                }

                // Special validation for contact info question (0.8)
                if (this.currentQuestion === 0.8) {
                    const nome = document.getElementById('contact_nome')?.value;
                    const email = document.getElementById('contact_email')?.value;
                    const whatsapp = document.getElementById('contact_whatsapp')?.value;
                    const nascimento = document.getElementById('contact_nascimento')?.value;

                    return nome && email && whatsapp && nascimento;
                }

                // Check if current question should be shown (for conditional questions)
                if (this.currentQuestion === 31 || this.currentQuestion === 32) {
                    const q30Value = document.querySelector('input[name="q30"]:checked')?.value;
                    if (q30Value === 'quero_comecar' || q30Value === 'nao_pratico') {
                        return true; // Skip validation for hidden questions (inactive people)
                    }

                    // If question should be shown, check if it's displayed
                    if (!currentElement.classList.contains('active')) {
                        return true; // Question not currently shown
                    }
                }
                
                // Check radio buttons
                const radioInputs = currentElement.querySelectorAll('input[type="radio"]');
                if (radioInputs.length > 0) {
                    return Array.from(radioInputs).some(input => input.checked);
                }

                // Check checkboxes (at least one must be checked if present)
                const checkboxInputs = currentElement.querySelectorAll('input[type="checkbox"]');
                if (checkboxInputs.length > 0) {
                    return Array.from(checkboxInputs).some(input => input.checked);
                }

                // Check required text inputs
                const requiredInputs = currentElement.querySelectorAll('input[required], textarea[required]');
                if (requiredInputs.length > 0) {
                    return Array.from(requiredInputs).every(input => input.value.trim() !== '');
                }
                
                // Check other inputs (optional) - only visible ones
                const otherInputs = currentElement.querySelectorAll('input:not([type="radio"]):not([type="checkbox"]):not([required]), textarea:not([required])');
                if (otherInputs.length > 0) {
                    // Only validate visible inputs (not in hidden containers)
                    const visibleInputs = Array.from(otherInputs).filter(input => {
                        const parent = input.closest('.other-text-container');
                        return !parent || parent.style.display !== 'none';
                    });

                    if (visibleInputs.length > 0) {
                        return visibleInputs.some(input => input.value.trim() !== '');
                    }
                }
                
                return true;
            }
            
            nextQuestion() {
                if (!this.isCurrentQuestionValid()) {
                    this.showValidationMessage();
                    return;
                }

                this.saveCurrentQuestionData();

                // First proceed to next question
                this.proceedToNext();
                
                // Celebration removed per user request
                // const celebrationMoments = [5, 10, 15, 20, 25];
                // if (celebrationMoments.includes(this.currentQuestion)) {
                //     this.showStrategicCelebration();
                // }
            }
            
            proceedToNext() {
                // Always use mixed flow (normal + DISC intercalated)
                if (this.questionOrder.length > 0) {
                    this.proceedMixed();
                    return;
                }

                // Fallback navigation (should not be reached in normal flow)
                if (this.currentQuestion === 'disc1') {
                    this.currentQuestion = 'disc2';
                } else if (this.currentQuestion === 'disc2') {
                    this.currentQuestion = 'disc3';
                } else if (this.currentQuestion === 'disc3') {
                    this.currentQuestion = 'disc4';
                } else if (this.currentQuestion === 'disc4') {
                    this.currentQuestion = 'disc5';
                } else if (this.currentQuestion === 'disc5') {
                    this.currentQuestion = 'disc6';
                } else if (this.currentQuestion === 'disc6') {
                    this.currentQuestion = 'disc7';
                } else if (this.currentQuestion === 'disc7') {
                    this.currentQuestion = 'disc8';
                } else if (this.currentQuestion === 'disc8') {
                    this.currentQuestion = 'disc9';
                } else if (this.currentQuestion === 'disc9') {
                    this.currentQuestion = 'disc10';
                } else if (this.currentQuestion === 'disc10') {
                    this.currentQuestion = 'disc11';
                } else if (this.currentQuestion === 'disc11') {
                    this.currentQuestion = 'disc12';
                } else if (this.currentQuestion === 'disc12') {
                    // After all DISC questions, go to contact info
                    this.currentQuestion = 0.8;
                } else if (this.currentQuestion === 0.8) {
                    // Contact info is the final screen - no more questions
                    // The submit button should be visible, handled by updateNavigation()
                    return;
                }
                // Handle conditional navigation for existing questions
                else if (this.currentQuestion === 30) {
                    // After question 30, update totalQuestions and navigate based on selection
                    const q30Value = document.querySelector('input[name="q30"]:checked')?.value;
                    if (q30Value === '3_5x' || q30Value === 'irregular') {
                        this.totalQuestions = 36; // Include conditional questions + new initial ones
                        this.currentQuestion = 31; // Go to question 31 for active people
                    } else if (q30Value === 'quero_comecar' || q30Value === 'nao_pratico') {
                        this.totalQuestions = 35; // Skip conditional questions
                        this.currentQuestion = 33; // Skip to question 33 for inactive people
                    } else {
                        this.totalQuestions = 35; // Default skip conditional questions
                        this.currentQuestion = 33; // Default skip to question 33
                    }
                } else if (this.currentQuestion === 31) {
                    this.currentQuestion = 32;
                } else if (this.currentQuestion === 32) {
                    this.currentQuestion = 33;
                } else if (this.currentQuestion < 34) { // Always use absolute max
                    this.currentQuestion++;
                }

                // Make sure we don't exceed the actual questions
                if (this.currentQuestion > 34) {
                    this.currentQuestion = 34;
                }

                this.showCurrentQuestion();
                this.updateProgress();
                this.updateNavigation();
            }
            
            proceedMixed() {
                // Navigation for mixed flow (normal questions + DISC intercalated in fixed pattern)

                // Navigate through the question order
                this.questionOrderIndex++;

                if (this.questionOrderIndex < this.questionOrder.length) {
                    this.currentQuestion = this.questionOrder[this.questionOrderIndex];
                } else {
                    // Finished all questions, go to contact info
                    this.currentQuestion = 0.8;
                }

                this.showCurrentQuestion();
                this.updateProgress();
                this.updateNavigation();
            }

            showStrategicCelebration() {
                const messages = [
                    "🎉 Ótimo progresso! Continue assim!",
                    "🌟 Você está indo muito bem!",  
                    "💪 Metade do caminho! Você consegue!",
                    "🚀 Quase lá! Mantém o foco!",
                    "⭐ Excelente! Falta pouco!"
                ];
                
                const messageIndex = Math.min(Math.floor(this.currentQuestion / 5) - 1, messages.length - 1);
                const celebrationTitle = document.querySelector('.celebration-title');
                const celebrationText = document.querySelector('.celebration-text');
                
                celebrationTitle.textContent = messages[messageIndex];
                celebrationText.textContent = 'Você está fazendo um ótimo trabalho! Continue respondendo para criarmos seu plano perfeito.';
                
                if (window.showCelebration) {
                    window.showCelebration();
                }
            }
            
            previousQuestion() {
                // Handle mixed flow (always active now)
                if (this.questionOrderIndex >= 0) {
                    if (this.currentQuestion === 0.8) {
                        // Go back to last question in mixed order
                        this.questionOrderIndex = this.questionOrder.length - 1;
                        this.currentQuestion = this.questionOrder[this.questionOrderIndex];
                    } else if (this.questionOrderIndex > 0) {
                        this.questionOrderIndex--;
                        this.currentQuestion = this.questionOrder[this.questionOrderIndex];
                    }
                    // If questionOrderIndex === 0, we're at the first question, can't go back
                    this.showCurrentQuestion();
                    this.updateProgress();
                    this.updateNavigation();
                    return;
                }

                // Fallback navigation (should not be reached in normal flow)
                if (this.currentQuestion === 0.8) {
                    this.currentQuestion = 'disc12'; // Go back to last DISC question
                } else if (this.currentQuestion === 'disc12') {
                    this.currentQuestion = 'disc11';
                } else if (this.currentQuestion === 'disc11') {
                    this.currentQuestion = 'disc10';
                } else if (this.currentQuestion === 'disc10') {
                    this.currentQuestion = 'disc9';
                } else if (this.currentQuestion === 'disc9') {
                    this.currentQuestion = 'disc8';
                } else if (this.currentQuestion === 'disc8') {
                    this.currentQuestion = 'disc7';
                } else if (this.currentQuestion === 'disc7') {
                    this.currentQuestion = 'disc6';
                } else if (this.currentQuestion === 'disc6') {
                    this.currentQuestion = 'disc5';
                } else if (this.currentQuestion === 'disc5') {
                    this.currentQuestion = 'disc4';
                } else if (this.currentQuestion === 'disc4') {
                    this.currentQuestion = 'disc3';
                } else if (this.currentQuestion === 'disc3') {
                    this.currentQuestion = 'disc2';
                } else if (this.currentQuestion === 'disc2') {
                    this.currentQuestion = 'disc1';
                }
                // Handle conditional navigation going back for existing questions
                else if (this.currentQuestion === 33) {
                    const q30Value = document.querySelector('input[name="q30"]:checked')?.value;
                    if (q30Value === '3_5x' || q30Value === 'irregular') {
                        this.currentQuestion = 32; // Go back to question 32 for active people
                    } else if (q30Value === 'quero_comecar' || q30Value === 'nao_pratico') {
                        this.currentQuestion = 30; // Go back to question 30 for inactive people
                    } else {
                        this.currentQuestion = 30; // Default go back to question 30
                    }
                } else if (this.currentQuestion === 32) {
                    this.currentQuestion = 31;
                } else if (this.currentQuestion === 31) {
                    this.currentQuestion = 30;
                } else if (this.currentQuestion > 1) {
                    this.currentQuestion--;
                }
                
                this.showCurrentQuestion();
                this.updateProgress();
                this.updateNavigation();
            }
            
            showCurrentQuestion() {
                // Hide all questions first
                this.questions.forEach(question => {
                    question.classList.remove('active');
                    question.style.display = 'none';
                });

                const currentElement = this.getCurrentQuestionElement();

                if (currentElement) {
                    // Check if this is a conditional question that should be shown
                    if (this.currentQuestion === 31 || this.currentQuestion === 32) {
                        const q30Value = document.querySelector('input[name="q30"]:checked')?.value;
                        if (q30Value === '3_5x' || q30Value === 'irregular') {
                            // Show the conditional question for active people
                            currentElement.style.display = 'block';
                            currentElement.classList.add('active');
                            this.animateOptions(currentElement);
                        } else {
                            // Skip this question for inactive people, move to next
                            this.currentQuestion = 33;
                            this.showCurrentQuestion();
                            return;
                        }
                    } else {
                        // Regular question, show normally
                        currentElement.style.display = 'block';
                        currentElement.classList.add('active');
                        this.animateOptions(currentElement);
                    }
                } else {
                    // If current element not found, skip to next question automatically
                    console.error(`Question element not found for question ${this.currentQuestion}. Skipping to next.`);

                    // In mixed flow, advance in the questionOrder array
                    if (this.flowType === 'mixed' && this.questionOrderIndex >= 0) {
                        this.questionOrderIndex++;
                        if (this.questionOrderIndex < this.questionOrder.length) {
                            this.currentQuestion = this.questionOrder[this.questionOrderIndex];
                            this.showCurrentQuestion();
                        } else {
                            // End of questions, go to contact
                            this.currentQuestion = 0.8;
                            this.showCurrentQuestion();
                        }
                    } else {
                        // In sequential flow, just increment
                        this.currentQuestion++;
                        this.showCurrentQuestion();
                    }
                }
            }
            
            animateOptions(container) {
                const options = container.querySelectorAll('.option, .checkbox-group');
                options.forEach((option, index) => {
                    option.style.animationDelay = `${index * 0.1}s`;
                });
            }
            
            proceedToNextQuestion() {
                // This method is called after celebration overlay - don't advance, just continue normally
                // The question was already advanced before showing celebration
                return;
            }
            
            updateProgress() {
                let questionsAnswered;

                // Handle mixed flow (always active now)
                if (this.questionOrderIndex >= 0) {
                    // Count: all answered in order + current
                    questionsAnswered = this.questionOrderIndex + 1;

                    if (this.currentQuestion === 0.8) {
                        questionsAnswered = this.totalQuestions;
                    }
                }
                // Handle contact info question
                else if (this.currentQuestion === 0.8) {
                    questionsAnswered = this.totalQuestions;
                }
                // Fallback (should not be reached in normal flow)
                else {
                    questionsAnswered = 1;
                }

                const progressPercentage = (questionsAnswered / this.totalQuestions) * 100;
                this.progressFill.style.width = `${progressPercentage}%`;
                this.progressText.textContent = `${Math.round(progressPercentage)}%`;
            }

            updateNavigation() {
                this.prevBtn.disabled = this.questionOrderIndex <= 0;

                // Show submit button ONLY on contact info screen (0.8)
                // This is the only place with contact fields that sendToWhatsApp() needs
                if (this.currentQuestion === 0.8) {
                    this.nextBtn.style.display = 'none';
                    this.submitBtn.style.display = 'block';
                } else {
                    this.nextBtn.style.display = 'block';
                    this.submitBtn.style.display = 'none';
                }
            }
            
            saveCurrentQuestionData() {
                const currentElement = this.getCurrentQuestionElement();
                if (!currentElement) return;

                // Check if this is a DISC question
                const isDISC = currentElement.hasAttribute('data-disc');

                // Get question text
                const questionTitle = currentElement.querySelector('.question-title, h2');
                const questionText = questionTitle ? questionTitle.textContent.trim() : '';

                // Save radio button selections
                const radioInputs = currentElement.querySelectorAll('input[type="radio"]:checked');
                radioInputs.forEach(input => {
                    if (isDISC) {
                        // Save DISC answers separately
                        this.discAnswers[input.name] = input.value;
                    }

                    // Get option text
                    const label = input.closest('label');
                    const optionText = label ? label.querySelector('.option-text, span')?.textContent.trim() : input.value;

                    // Save with question and answer
                    this.formData[input.name] = {
                        pergunta: questionText,
                        resposta: optionText || input.value,
                        valor: input.value
                    };
                });

                // Save checkbox selections
                const checkboxInputs = currentElement.querySelectorAll('input[type="checkbox"]:checked');
                if (checkboxInputs.length > 0) {
                    const checkboxValues = Array.from(checkboxInputs).map(input => {
                        const label = input.closest('label');
                        const optionText = label ? label.querySelector('.checkbox-text, span')?.textContent.trim() : input.value;
                        return {
                            valor: input.value,
                            texto: optionText || input.value
                        };
                    });

                    // Use the first checkbox's name as the key
                    const firstCheckboxName = checkboxInputs[0].name;
                    this.formData[firstCheckboxName] = {
                        pergunta: questionText,
                        respostas: checkboxValues
                    };
                }

                // Save text inputs
                const textInputs = currentElement.querySelectorAll('input:not([type="radio"]):not([type="checkbox"]), textarea');
                textInputs.forEach(input => {
                    if (input.value.trim() !== '') {
                        const label = currentElement.querySelector(`label[for="${input.id}"], .input-label`);
                        const labelText = label ? label.textContent.trim() : questionText;

                        this.formData[input.name] = {
                            pergunta: labelText,
                            resposta: input.value.trim()
                        };
                    }
                });
            }
            
            showValidationMessage() {
                // Create and show a subtle validation message
                const currentElement = this.getCurrentQuestionElement();
                
                // Remove existing validation message
                const existingMessage = currentElement.querySelector('.validation-message');
                if (existingMessage) {
                    existingMessage.remove();
                }
                
                const validationMessage = document.createElement('div');
                validationMessage.className = 'validation-message';
                validationMessage.innerHTML = '<p>Por favor, responda esta pergunta para continuar.</p>';
                validationMessage.style.cssText = `
                    background: linear-gradient(135deg, #fed6e3 0%, #fef7ff 100%);
                    border: 1px solid #f7b2bd;
                    border-radius: 12px;
                    padding: 15px 20px;
                    margin-top: 20px;
                    color: #a0425c;
                    font-size: 0.9rem;
                    animation: shake 0.5s ease-in-out;
                `;
                
                currentElement.appendChild(validationMessage);
                
                // Add shake animation
                const style = document.createElement('style');
                style.textContent = `
                    @keyframes shake {
                        0%, 100% { transform: translateX(0); }
                        25% { transform: translateX(-5px); }
                        75% { transform: translateX(5px); }
                    }
                `;
                document.head.appendChild(style);
                
                setTimeout(() => {
                    if (validationMessage.parentNode) {
                        validationMessage.style.opacity = '0';
                        setTimeout(() => {
                            if (validationMessage.parentNode) {
                                validationMessage.remove();
                            }
                        }, 300);
                    }
                }, 3000);
            }
            
            async submitForm() {
                if (!this.isCurrentQuestionValid()) {
                    this.showValidationMessage();
                    return;
                }
                
                this.saveCurrentQuestionData();

                // Validate required fields
                const requiredFields = ['nome_completo', 'data_nascimento', 'whatsapp', 'email'];
                const missingFields = requiredFields.filter(field => {
                    const data = this.formData[field];
                    // Check if field exists and has a value (either string or object with resposta)
                    return !data || (typeof data === 'object' && !data.resposta);
                });

                if (missingFields.length > 0) {
                    alert('Por favor, preencha todos os campos obrigatórios.');
                    return;
                }
                
                this.showLoading();
                
                try {
                    // Prepare data for submission
                    const submissionData = {
                        timestamp: new Date().toISOString(),
                        respostas: this.formData
                    };
                    
                    const response = await fetch('https://workspace.n8n.automatech.tech/webhook/salvardados', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(submissionData)
                    });
                    
                    if (response.ok) {
                        this.showThankYou();
                    } else {
                        throw new Error('Erro ao enviar dados');
                    }
                } catch (error) {
                    console.error('Erro:', error);
                    this.hideLoading();
                    alert('Erro ao enviar formulário. Tente novamente.');
                }
            }
            
            showLoading() {
                this.loadingOverlay.style.display = 'flex';
                setTimeout(() => {
                    this.loadingOverlay.style.opacity = '1';
                }, 50);
            }
            
            hideLoading() {
                this.loadingOverlay.style.opacity = '0';
                setTimeout(() => {
                    this.loadingOverlay.style.display = 'none';
                }, 300);
            }
            
            showThankYou() {
                this.hideLoading();
                
                // Hide current question and show thank you
                this.questions.forEach(question => {
                    question.classList.remove('active');
                });
                
                const thankYouContainer = document.getElementById('thankYouContainer');
                thankYouContainer.style.display = 'block';
                thankYouContainer.classList.add('active');
                
                // Hide navigation
                document.querySelector('.navigation').style.display = 'none';
                
                // Update progress to 100%
                this.progressFill.style.width = '100%';
                this.progressText.textContent = '100%';
            }
            
            // Utility method to get all form data as JSON
            getAllFormData() {
                const allData = {};
                
                // Get all form inputs
                const allInputs = this.form.querySelectorAll('input, textarea, select');
                
                allInputs.forEach(input => {
                    if (input.type === 'radio' && input.checked) {
                        allData[input.name] = input.value;
                    } else if (input.type === 'checkbox' && input.checked) {
                        if (!allData[input.name]) {
                            allData[input.name] = [];
                        }
                        if (Array.isArray(allData[input.name])) {
                            allData[input.name].push(input.value);
                        } else {
                            allData[input.name] = [allData[input.name], input.value];
                        }
                    } else if (input.type !== 'radio' && input.type !== 'checkbox' && input.value.trim() !== '') {
                        allData[input.name] = input.value.trim();
                    }
                });
                
                return allData;
            }
        }

        // Initialize the form when DOM is loaded
        // Função para mostrar/esconder dicas
        function toggleTip(questionNumber) {
            const tipContent = document.getElementById(`tip-${questionNumber}`);
            if (tipContent.style.display === 'none' || tipContent.style.display === '') {
                tipContent.style.display = 'block';
            } else {
                tipContent.style.display = 'none';
            }
        }

        // Global variable to prevent multiple form instances
        let nutritionFormInstance = null;

        document.addEventListener('DOMContentLoaded', () => {
            // Initialize welcome screen functionality
            const welcomeScreen = document.getElementById('welcomeScreen');
            const mainContainer = document.getElementById('mainContainer');
            const startBtn = document.getElementById('startBtn');
            const celebrationOverlay = document.getElementById('celebrationOverlay');
            const continueBtn = document.getElementById('continueBtn');
            
            // Ensure start button exists before adding event listener
            if (!startBtn) {
                return;
            }
            
            // Handle start button click
            startBtn.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Fade out welcome screen
                welcomeScreen.style.transition = 'all 0.5s ease-out';
                welcomeScreen.style.opacity = '0';
                welcomeScreen.style.transform = 'scale(0.95)';
                
                setTimeout(() => {
                    welcomeScreen.style.display = 'none';
                    mainContainer.style.display = 'flex';
                    mainContainer.style.opacity = '0';
                    
                    // Fade in main container
                    setTimeout(() => {
                        mainContainer.style.transition = 'opacity 0.3s ease-out';
                        mainContainer.style.opacity = '1';

                        // Initialize nutrition form only if not already created
                        if (!nutritionFormInstance) {
                            nutritionFormInstance = new NutritionForm();
                        }
                    }, 100);
                }, 500);
            });
            
            // Handle celebration overlay
            window.showCelebration = () => {
                celebrationOverlay.style.display = 'flex';
                setTimeout(() => {
                    celebrationOverlay.style.opacity = '1';
                }, 50);
            };
            
            if (continueBtn) {
                continueBtn.addEventListener('click', () => {
                    celebrationOverlay.style.opacity = '0';
                    setTimeout(() => {
                        celebrationOverlay.style.display = 'none';
                        if (nutritionFormInstance) {
                            nutritionFormInstance.proceedToNextQuestion();
                        }
                    }, 500);
                });
            }
        });

        // Alternative initialization method if the first one fails
        window.addEventListener('load', () => {
            const startBtn = document.getElementById('startBtn');
            
            if (startBtn && !startBtn.hasAttribute('data-initialized')) {
                startBtn.setAttribute('data-initialized', 'true');
                startBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const welcomeScreen = document.getElementById('welcomeScreen');
                    const mainContainer = document.getElementById('mainContainer');
                    
                    if (welcomeScreen && mainContainer) {
                        welcomeScreen.style.display = 'none';
                        mainContainer.style.display = 'flex';
                        mainContainer.style.opacity = '1';

                        // Initialize form only if not already created
                        setTimeout(() => {
                            if (!nutritionFormInstance) {
                                nutritionFormInstance = new NutritionForm();
                            }
                        }, 200);
                    }
                });
            }
        });

        // Add smooth scrolling and better UX
        document.addEventListener('DOMContentLoaded', () => {
            // Add ripple effect to buttons
            document.querySelectorAll('.nav-btn, .option').forEach(element => {
                element.addEventListener('click', function(e) {
                    const ripple = document.createElement('span');
                    const rect = this.getBoundingClientRect();
                    const size = Math.max(rect.width, rect.height);
                    const x = e.clientX - rect.left - size / 2;
                    const y = e.clientY - rect.top - size / 2;
                    
                    ripple.style.cssText = `
                        position: absolute;
                        width: ${size}px;
                        height: ${size}px;
                        left: ${x}px;
                        top: ${y}px;
                        background: rgba(168, 237, 234, 0.3);
                        border-radius: 50%;
                        transform: scale(0);
                        animation: ripple 0.6s ease-out;
                        pointer-events: none;
                    `;
                    
                    this.style.position = 'relative';
                    this.style.overflow = 'hidden';
                    this.appendChild(ripple);
                    
                    setTimeout(() => ripple.remove(), 600);
                });
            });
            
            // Add ripple animation to head
            const style = document.createElement('style');
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(2);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);

            // Handle "Outras" checkbox to show/hide text input for Q29
            const q29OutrasCheckbox = document.getElementById('q29_outras');
            const q29OutrasContainer = document.getElementById('q29_outras_container');
            const q29OutrasTexto = document.getElementById('q29_outras_texto');

            if (q29OutrasCheckbox && q29OutrasContainer) {
                q29OutrasCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        q29OutrasContainer.style.display = 'block';
                        q29OutrasTexto.focus();
                    } else {
                        q29OutrasContainer.style.display = 'none';
                        q29OutrasTexto.value = ''; // Clear the text when unchecked
                    }
                });
            }
        });
