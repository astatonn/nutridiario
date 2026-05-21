<?php

/**
 * Teste completo do fluxo do formulário
 * Execute: php tests/test_form_flow.php
 */

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "==========================================\n";
echo "TESTE DO FLUXO COMPLETO DO FORMULÁRIO\n";
echo "==========================================\n\n";

// Simulate complete form submission data
$formData = [
    'tipo' => 'nova_dieta_completa',
    'nome_completo' => 'Teste Fluxo Completo',
    'email' => 'teste.fluxo@nutridiario.com.br',
    'whatsapp' => '(11) 99887-7665',
    'data_nascimento' => '1992-03-20',
    'fluxo' => 'randomized',
    'respostas_formulario' => [
        'q0' => [
            'pergunta' => 'Você tem uma dieta?',
            'resposta' => 'Não',
            'valor' => 'nao'
        ],
        'q1' => [
            'pergunta' => 'Qual é o seu principal objetivo?',
            'resposta' => 'Emagrecer',
            'valor' => 'emagrecer'
        ],
        'q2' => [
            'pergunta' => 'Qual é seu sexo biológico?',
            'resposta' => 'Masculino',
            'valor' => 'masculino'
        ],
    ],
    'respostas_disc' => [
        'disc_1' => 'A',
        'disc_2' => 'B',
        'disc_3' => 'C',
    ],
    'pontuacao_disc' => [
        'A' => 5,
        'B' => 3,
        'C' => 2,
        'D' => 2,
        'perfil_dominante' => 'A',
    ]
];

echo "📝 TESTE 1: Envio para Supabase\n";
echo "-------------------------------------------\n";

try {
    $supabase = new App\Services\SupabaseService();
    $controller = new App\Http\Controllers\PlanoAlimentarController($supabase);

    $request = Request::create('/planoalimentar', 'POST', $formData);

    echo "Enviando dados...\n";
    echo "Nome: {$formData['nome_completo']}\n";
    echo "Email: {$formData['email']}\n\n";

    $response = $controller->store($request);
    $statusCode = $response->getStatusCode();
    $responseData = json_decode($response->getContent(), true);

    if ($statusCode === 201) {
        echo "✅ SUCESSO! Dados salvos no Supabase\n";
        echo "Status: $statusCode\n";

        if (isset($responseData['data'][0]['id'])) {
            $recordId = $responseData['data'][0]['id'];
            echo "ID do registro: $recordId\n";
        }

        echo "\n";
    } else {
        echo "❌ ERRO! Status: $statusCode\n";
        echo "Response: " . json_encode($responseData, JSON_PRETTY_PRINT) . "\n\n";
    }

} catch (Exception $e) {
    echo "❌ EXCEÇÃO: " . $e->getMessage() . "\n\n";
}

echo "==========================================\n";
echo "📝 TESTE 2: Verificar dados no Supabase\n";
echo "==========================================\n\n";

try {
    $supabase = new App\Services\SupabaseService();

    echo "Buscando últimos 3 registros...\n\n";
    $records = $supabase->getAllNutriDiario();

    if ($records && count($records) > 0) {
        echo "✅ Total de registros: " . count($records) . "\n\n";

        // Show last 3
        $lastRecords = array_slice($records, 0, 3);

        foreach ($lastRecords as $index => $record) {
            echo "--- Registro " . ($index + 1) . " ---\n";
            echo "ID: {$record['id']}\n";
            echo "Nome: {$record['nome']}\n";
            echo "Email: {$record['email']}\n";
            echo "WhatsApp: {$record['whatsapp']}\n";

            $forms = is_string($record['respostas_forms'])
                ? json_decode($record['respostas_forms'], true)
                : $record['respostas_forms'];

            echo "Tipo: " . ($forms['tipo'] ?? 'N/A') . "\n";

            if (isset($forms['respostas_formulario'])) {
                echo "Total respostas: " . count($forms['respostas_formulario']) . "\n";
            }

            echo "Criado: {$record['created_at']}\n";
            echo "\n";
        }

    } else {
        echo "⚠️  Nenhum registro encontrado\n\n";
    }

} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n\n";
}

echo "==========================================\n";
echo "📝 TESTE 3: Testar webhook n8n\n";
echo "==========================================\n\n";

echo "Nota: O webhook n8n é chamado via JavaScript no navegador.\n";
echo "Teste manual necessário:\n";
echo "1. Acesse /planoalimentar\n";
echo "2. Preencha o formulário\n";
echo "3. Clique em 'Finalizar'\n";
echo "4. Verifique:\n";
echo "   ✓ Loading aparece\n";
echo "   ✓ Dados são enviados (check console do navegador)\n";
echo "   ✓ Modal de sucesso aparece\n";
echo "   ✓ Mensagem: 'Muito obrigado! Vamos entrar em contato...'\n\n";

echo "==========================================\n";
echo "✅ RESUMO DOS TESTES\n";
echo "==========================================\n\n";

echo "1. ✅ Envio para Supabase: " . ($statusCode === 201 ? "FUNCIONANDO" : "COM ERRO") . "\n";
echo "2. ✅ Leitura do Supabase: " . (isset($records) && count($records) > 0 ? "FUNCIONANDO" : "COM ERRO") . "\n";
echo "3. ⚠️  Webhook n8n: TESTE MANUAL NECESSÁRIO\n";
echo "4. ⚠️  Modal de sucesso: TESTE MANUAL NECESSÁRIO\n\n";

echo "==========================================\n";
echo "🔍 CHECKLIST DE VERIFICAÇÃO MANUAL\n";
echo "==========================================\n\n";

echo "No navegador, verifique:\n\n";
echo "□ Ao chegar na tela de dados de contato (0.8):\n";
echo "  □ Botão 'Próximo' NÃO aparece\n";
echo "  □ Botão 'FINALIZAR' APARECE\n\n";

echo "□ Ao clicar em 'Finalizar':\n";
echo "  □ Loading overlay aparece\n";
echo "  □ Console mostra envio para /planoalimentar (200/201)\n";
echo "  □ Console mostra envio para webhook n8n\n";
echo "  □ Loading desaparece\n";
echo "  □ Modal de sucesso aparece\n\n";

echo "□ Modal de sucesso contém:\n";
echo "  □ Ícone de check (✓) animado\n";
echo "  □ Título: 'Muito obrigado!'\n";
echo "  □ Mensagem: 'Vamos entrar em contato com você no seu WhatsApp em breve!'\n";
echo "  □ Botão: 'Voltar ao Início'\n\n";

echo "==========================================\n";
echo "TESTE CONCLUÍDO\n";
echo "==========================================\n";
