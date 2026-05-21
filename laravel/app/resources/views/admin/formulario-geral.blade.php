@extends('layouts.admin')

@section('title', 'Formulário Geral - Respostas')
@section('page-title', 'Formulário Geral')

@push('styles')
<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 2% auto;
        padding: 0;
        border-radius: 10px;
        width: 90%;
        max-width: 900px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 5px 30px rgba(0,0,0,0.3);
    }

    .modal-header {
        background: #3498db;
        color: white;
        padding: 20px 25px;
        border-radius: 10px 10px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        margin: 0;
        font-size: 20px;
    }

    .modal-body {
        padding: 25px;
    }

    .close {
        color: white;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
        background: none;
        border: none;
        padding: 0;
        width: 30px;
        height: 30px;
        line-height: 1;
    }

    .close:hover {
        opacity: 0.7;
    }

    .detail-section {
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e0e0e0;
    }

    .detail-section:last-child {
        border-bottom: none;
    }

    .detail-section h3 {
        color: #3498db;
        font-size: 16px;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .detail-item {
        margin-bottom: 12px;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 5px;
    }

    .detail-label {
        font-weight: 600;
        color: #555;
        display: block;
        margin-bottom: 5px;
        font-size: 13px;
    }

    .detail-value {
        color: #333;
        font-size: 14px;
    }

    .badge-perfil {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
        margin-right: 5px;
    }

    .perfil-A { background: #ffe5e5; color: #dc3545; }
    .perfil-B { background: #fff8e1; color: #ff9800; }
    .perfil-C { background: #e8f5e9; color: #28a745; }
    .perfil-D { background: #e3f2fd; color: #007bff; }

    /* Responsividade da tabela */
    @media (max-width: 1024px) {
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            min-width: 900px;
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        table {
            font-size: 12px;
            min-width: 800px;
        }

        table th,
        table td {
            padding: 10px 8px !important;
            font-size: 12px !important;
        }

        .btn-sm {
            padding: 6px 10px !important;
            font-size: 11px !important;
        }

        .modal-content {
            width: 95%;
            margin: 5% auto;
        }

        .modal-body {
            padding: 15px;
        }
    }
</style>
@endpush

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ is_array($dados) ? count($dados) : 0 }}</div>
        <div class="stat-label">Total de Formulários Recebidos</div>
    </div>
</div>

<div class="content-card">
    @if($dados && is_array($dados) && count($dados) > 0)
        <div class="table-responsive">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #3498db; color: white;">
                    <tr>
                        <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">Data</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">Nome</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">E-mail</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">WhatsApp</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">Tipo</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">Perfil DISC</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">Ações</th>
                    </tr>
                </thead>
            <tbody>
                @foreach($dados as $index => $registro)
                    @php
                        $respostasForm = $registro['respostas_forms'] ?? null;
                        $tipo = 'N/A';
                        $tipoLabel = 'N/A';
                        $perfilDISC = 'N/A';
                        $perfilCor = 'N/A';
                        $perfilLetra = 'N/A';

                        // Mapeamento DISC: Letra -> Cor
                        $discMapping = [
                            'A' => ['letra' => 'A', 'cor' => 'Vermelho', 'nome' => 'Dominância'],
                            'B' => ['letra' => 'B', 'cor' => 'Amarelo', 'nome' => 'Influência'],
                            'C' => ['letra' => 'C', 'cor' => 'Verde', 'nome' => 'Estabilidade'],
                            'D' => ['letra' => 'D', 'cor' => 'Azul', 'nome' => 'Conformidade'],
                        ];

                        // Mapeamento Cor -> Letra (reverso)
                        $corToLetra = [
                            'vermelho' => 'A',
                            'amarelo' => 'B',
                            'verde' => 'C',
                            'azul' => 'D',
                        ];

                        // Mapeamento de tipos mais elaborados
                        $tipoMapping = [
                            'express' => 'Diagnóstico Express',
                            'completo' => 'Diagnóstico Completo',
                            'rapido' => 'Diagnóstico Rápido',
                            'detalhado' => 'Diagnóstico Detalhado',
                        ];

                        if ($respostasForm) {
                            $tipo = $respostasForm['tipo'] ?? 'N/A';
                            $tipoLabel = $tipoMapping[strtolower($tipo)] ?? ucfirst($tipo);

                            // Tentar pegar o perfil DISC
                            if (isset($respostasForm['DISC']['pontuacao']['perfil_dominante'])) {
                                $perfilLetra = strtoupper($respostasForm['DISC']['pontuacao']['perfil_dominante']);
                                if (isset($discMapping[$perfilLetra])) {
                                    $perfilCor = $discMapping[$perfilLetra]['cor'];
                                    $perfilDISC = $perfilLetra;
                                }
                            } elseif (isset($respostasForm['resultado']['cor_dominante'])) {
                                $corDominante = strtolower($respostasForm['resultado']['cor_dominante']);
                                if (isset($corToLetra[$corDominante])) {
                                    $perfilLetra = $corToLetra[$corDominante];
                                    $perfilCor = ucfirst($corDominante);
                                    $perfilDISC = $perfilLetra;
                                }
                            }
                        }

                        $createdAt = isset($registro['created_at'])
                            ? \Carbon\Carbon::parse($registro['created_at'])->format('d/m/Y H:i')
                            : 'N/A';
                    @endphp
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 15px; font-size: 14px; color: #333;">{{ $createdAt }}</td>
                        <td style="padding: 15px; font-size: 14px; color: #333;">{{ $registro['nome'] ?? 'N/A' }}</td>
                        <td style="padding: 15px; font-size: 14px; color: #333;">{{ $registro['email'] ?? 'N/A' }}</td>
                        <td style="padding: 15px; font-size: 14px; color: #333;">{{ $registro['whatsapp'] ?? 'N/A' }}</td>
                        <td style="padding: 15px; font-size: 14px; color: #333;">
                            <span style="background: #e3f2fd; color: #007bff; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                {{ $tipoLabel }}
                            </span>
                        </td>
                        <td style="padding: 15px; font-size: 14px;">
                            @if($perfilCor !== 'N/A')
                                <span class="badge-perfil perfil-{{ $perfilDISC }}">{{ $perfilCor }}</span>
                            @else
                                <span style="color: #999;">-</span>
                            @endif
                        </td>
                        <td style="padding: 15px;">
                            <div style="display: flex; gap: 8px;">
                                <button onclick="showDetails({{ $index }})" class="btn btn-sm">Ver Detalhes</button>
                                <form method="POST" action="{{ route('admin.formulario-geral.delete', $registro['id']) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm" style="background: #dc3545;" onclick="return confirm('Arquivar este registro? Ele poderá ser restaurado depois.')">
                                        Arquivar
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @else
        <div style="padding: 60px 20px; text-align: center; color: #999;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 80px; height: 80px; margin: 0 auto 20px; opacity: 0.3;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p>Nenhum formulário foi recebido ainda.</p>
        </div>
    @endif
</div>

<!-- Modal -->
<div id="detailsModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Detalhes do Formulário</h2>
            <button class="close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body" id="modalBody">
            <!-- Content will be loaded here by JavaScript -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const dadosCompletos = @json($dados ?? []);

// Mapeamento DISC: Letra -> Cor/Nome
const discMapping = {
    'A': { letra: 'A', cor: 'Vermelho', nome: 'Dominância' },
    'B': { letra: 'B', cor: 'Amarelo', nome: 'Influência' },
    'C': { letra: 'C', cor: 'Verde', nome: 'Estabilidade' },
    'D': { letra: 'D', cor: 'Azul', nome: 'Conformidade' }
};

// Mapeamento Cor -> Letra (reverso)
const corToLetra = {
    'vermelho': 'A',
    'amarelo': 'B',
    'verde': 'C',
    'azul': 'D'
};

function getPerfilInfo(perfil) {
    const letraMaiuscula = String(perfil).toUpperCase();
    if (discMapping[letraMaiuscula]) {
        return discMapping[letraMaiuscula];
    }

    const letraMinuscula = String(perfil).toLowerCase();
    if (corToLetra[letraMinuscula]) {
        return discMapping[corToLetra[letraMinuscula]];
    }

    return { letra: perfil, cor: perfil, nome: '' };
}

function showDetails(index) {
    const registro = dadosCompletos[index];
    if (!registro) return;

    const respostasForm = registro.respostas_forms || {};
    const respostasFormulario = respostasForm.respostas_formulario || {};
    const disc = respostasForm.DISC || {};
    const respostasArray = respostasForm.respostas || [];

    let html = `
        <div class="detail-section">
            <h3>📋 Informações Pessoais</h3>
            <div class="detail-item">
                <span class="detail-label">Nome:</span>
                <span class="detail-value">${registro.nome || 'N/A'}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">E-mail:</span>
                <span class="detail-value">${registro.email || 'N/A'}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">WhatsApp:</span>
                <span class="detail-value">${registro.whatsapp || 'N/A'}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Data de Nascimento:</span>
                <span class="detail-value">${registro.data_nascimentno || 'N/A'}</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Data de Envio:</span>
                <span class="detail-value">${new Date(registro.created_at).toLocaleString('pt-BR')}</span>
            </div>
        </div>
    `;

    // Análise DISC
    if (disc.pontuacao) {
        const pontuacao = disc.pontuacao;
        const perfilDominanteInfo = getPerfilInfo(pontuacao.perfil_dominante);
        const perfilSecundarioInfo = pontuacao.perfil_secundario ? getPerfilInfo(pontuacao.perfil_secundario) : null;

        html += `
            <div class="detail-section">
                <h3>🎯 Análise de Perfil DISC</h3>
                <div class="detail-item">
                    <span class="detail-label">Perfil Dominante:</span>
                    <span class="detail-value">
                        <span class="badge-perfil perfil-${perfilDominanteInfo.letra}">${perfilDominanteInfo.cor}</span>
                        ${perfilDominanteInfo.nome ? `- ${perfilDominanteInfo.nome}` : ''}
                    </span>
                </div>
                ${perfilSecundarioInfo ? `
                <div class="detail-item">
                    <span class="detail-label">Perfil Secundário:</span>
                    <span class="detail-value">
                        <span class="badge-perfil perfil-${perfilSecundarioInfo.letra}">${perfilSecundarioInfo.cor}</span>
                        ${perfilSecundarioInfo.nome ? `- ${perfilSecundarioInfo.nome}` : ''}
                    </span>
                </div>
                ` : ''}
                <div class="detail-item">
                    <span class="detail-label">Pontuação:</span>
                    <span class="detail-value">
                        Vermelho: ${pontuacao.total_A || 0} |
                        Amarelo: ${pontuacao.total_B || 0} |
                        Verde: ${pontuacao.total_C || 0} |
                        Azul: ${pontuacao.total_D || 0}
                    </span>
                </div>
            </div>
        `;
    } else if (respostasForm.resultado) {
        const resultado = respostasForm.resultado;
        const corDominanteInfo = getPerfilInfo(resultado.cor_dominante);
        html += `
            <div class="detail-section">
                <h3>🎯 Resultado do Teste</h3>
                <div class="detail-item">
                    <span class="detail-label">Cor Dominante:</span>
                    <span class="detail-value">
                        <span class="badge-perfil perfil-${corDominanteInfo.letra}">${corDominanteInfo.cor}</span>
                        ${corDominanteInfo.nome ? `- ${corDominanteInfo.nome}` : ''}
                    </span>
                </div>
                ${resultado.cores_count ? `
                <div class="detail-item">
                    <span class="detail-label">Distribuição de Cores:</span>
                    <span class="detail-value">${JSON.stringify(resultado.cores_count, null, 2)}</span>
                </div>
                ` : ''}
            </div>
        `;
    }

    // Respostas do formulário
    if (Object.keys(respostasFormulario).length > 0) {
        html += `<div class="detail-section"><h3>📝 Respostas do Formulário</h3>`;

        for (const [key, value] of Object.entries(respostasFormulario)) {
            if (typeof value === 'object' && value !== null) {
                if (Array.isArray(value)) {
                    html += `
                        <div class="detail-item">
                            <span class="detail-label">${key}:</span>
                            <span class="detail-value">${value.map(v => v.texto || v.valor || v).join(', ')}</span>
                        </div>
                    `;
                } else {
                    html += `
                        <div class="detail-item">
                            <span class="detail-label">${value.pergunta || key}:</span>
                            <span class="detail-value">${value.resposta || value.valor || JSON.stringify(value)}</span>
                        </div>
                    `;
                }
            } else {
                html += `
                    <div class="detail-item">
                        <span class="detail-label">${key}:</span>
                        <span class="detail-value">${value}</span>
                    </div>
                `;
            }
        }

        html += `</div>`;
    }

    // Respostas diretas (formato alternativo)
    if (respostasArray.length > 0) {
        html += `<div class="detail-section"><h3>📝 Respostas do Teste</h3>`;

        respostasArray.forEach((resposta, idx) => {
            let corInfo = null;
            if (resposta.cor) {
                corInfo = getPerfilInfo(resposta.cor);
            }

            html += `
                <div class="detail-item">
                    <span class="detail-label">Pergunta ${resposta.pergunta_numero || idx + 1}:</span>
                    <span class="detail-value">
                        <strong>${resposta.pergunta_texto || 'N/A'}</strong><br>
                        Resposta: ${resposta.opcao_texto || 'N/A'}
                        ${corInfo ? `<span class="badge-perfil perfil-${corInfo.letra}">${corInfo.cor}</span>` : ''}
                    </span>
                </div>
            `;
        });

        html += `</div>`;
    }

    // Análise comportamental (IA)
    const analiseExistente = @json($analises ?? []);
    const analise = analiseExistente[registro.id];

    html += `
        <div class="detail-section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3 style="margin: 0;">🤖 Análise Comportamental e Plano Alimentar (IA)</h3>
                <button id="generateAnalysisBtn_${index}" class="btn btn-sm" onclick="generateAnalysisFormularioGeral(${registro.id}, ${index})" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <span id="btnText_${index}">${analise ? 'Gerar Nova Análise' : 'Gerar Análise'}</span>
                    <span id="btnLoading_${index}" style="display: none;">Gerando...</span>
                </button>
            </div>
            <div id="analysisContent_${index}" style="${analise ? '' : 'display: none;'}">
                <div id="analysisText_${index}" style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid #667eea; line-height: 1.8; color: #2c3e50;">${analise ? analise.analise : ''}</div>
            </div>
            <div id="analysisPlaceholder_${index}" style="${analise ? 'display: none;' : ''} text-align: center; color: #999; padding: 30px;">
                Clique no botão acima para gerar um plano alimentar comportamental completo usando Inteligência Artificial.
            </div>
        </div>
    `;

    document.getElementById('modalBody').innerHTML = html;
    document.getElementById('detailsModal').style.display = 'block';

    // Format existing analysis if available
    if (analise) {
        const analysisText = document.getElementById(`analysisText_${index}`);
        if (analysisText) {
            analysisText.innerHTML = formatMarkdown(analysisText.textContent);
        }
    }
}

function formatMarkdown(text) {
    let html = text;
    html = html.replace(/^### (.+)$/gm, '<h3>$1</h3>');
    html = html.replace(/^## (.+)$/gm, '<h2>$1</h2>');
    html = html.replace(/^# (.+)$/gm, '<h1>$1</h1>');
    html = html.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
    html = html.replace(/\*(.+?)\*/g, '<em>$1</em>');
    html = html.replace(/^(\d+)\.\s+(.+)$/gm, function(match, num, content) {
        return '<div style="margin-bottom: 15px;"><strong style="color: #667eea; font-size: 16px;">' + num + '. ' + content.replace(/\*\*(.+?)\*\*/g, '$1') + '</strong></div>';
    });
    html = html.replace(/^   - (.+)$/gm, '<div style="margin-left: 25px; margin-bottom: 8px; padding-left: 15px; border-left: 2px solid #e0e0e0;">• $1</div>');
    html = html.replace(/^- (.+)$/gm, '<div style="margin-bottom: 8px;">• $1</div>');
    html = html.split('\n\n').map(para => {
        if (!para.trim().startsWith('<')) {
            return '<p>' + para + '</p>';
        }
        return para;
    }).join('\n');
    return html;
}

function generateAnalysisFormularioGeral(id, index) {
    const btn = document.getElementById(`generateAnalysisBtn_${index}`);
    const btnText = document.getElementById(`btnText_${index}`);
    const btnLoading = document.getElementById(`btnLoading_${index}`);
    const content = document.getElementById(`analysisContent_${index}`);
    const placeholder = document.getElementById(`analysisPlaceholder_${index}`);
    const analysisText = document.getElementById(`analysisText_${index}`);

    btn.disabled = true;
    btnText.style.display = 'none';
    btnLoading.style.display = 'inline';

    fetch(`/admin/formulario-geral/${id}/gerar-analise`, {
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

function closeModal() {
    document.getElementById('detailsModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('detailsModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>
@endpush
