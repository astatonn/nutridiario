<?php

/**
 * Script de teste para integração com Supabase
 * Execute: php tests/test_supabase.php
 */

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Create mock data - Plano Alimentar completo
$planoAlimentarData = [
    'tipo' => 'nova_dieta_completa',
    'nome_completo' => 'Maria Teste da Silva',
    'email' => 'maria.teste@nutridiario.com.br',
    'whatsapp' => '(11) 98765-4321',
    'data_nascimento' => '1985-05-15',
    'fluxo' => 'nova_dieta',
    'respostas_formulario' => [
        'q0' => [
            'pergunta' => 'Você tem uma dieta?',
            'resposta' => 'Não',
            'valor' => 'nao'
        ],
        'q1' => [
            'pergunta' => 'Qual é o seu principal objetivo?',
            'resposta' => 'Perder peso e ganhar saúde',
            'valor' => 'perder_peso'
        ],
        'q2' => [
            'pergunta' => 'Qual é seu sexo biológico?',
            'resposta' => 'Feminino',
            'valor' => 'feminino'
        ],
        'q3' => [
            'pergunta' => 'Qual é sua idade?',
            'resposta' => '38 anos',
            'valor' => '38'
        ],
        'q4' => [
            'pergunta' => 'Qual é seu peso atual?',
            'resposta' => '75.5 kg',
            'valor' => '75.5'
        ],
        'q5' => [
            'pergunta' => 'Qual é sua altura?',
            'resposta' => '165 cm',
            'valor' => '165'
        ],
        'q6' => [
            'pergunta' => 'Você tem alguma restrição alimentar?',
            'resposta' => 'Não tenho restrições',
            'valor' => 'nao'
        ],
        'q7' => [
            'pergunta' => 'Você pratica atividade física?',
            'resposta' => 'Sim, 3-5 vezes por semana',
            'valor' => '3_5x'
        ],
        'q8' => [
            'pergunta' => 'Qual seu nível de estresse?',
            'resposta' => 'Moderado',
            'valor' => 'moderado'
        ],
        'q9' => [
            'pergunta' => 'Quantas horas você dorme por noite?',
            'resposta' => '6-7 horas',
            'valor' => '6_7h'
        ],
        'q10' => [
            'pergunta' => 'Você bebe água regularmente?',
            'resposta' => 'Sim, mais de 2 litros',
            'valor' => 'sim_2l'
        ],
        'q14_alimentos_nao_gosta' => [
            'pergunta' => 'Quais alimentos você NÃO gosta ou não come?',
            'respostas' => [
                ['valor' => 'figado', 'texto' => 'Fígado'],
                ['valor' => 'brocolis', 'texto' => 'Brócolis'],
                ['valor' => 'couve_flor', 'texto' => 'Couve-flor']
            ]
        ]
    ],
    'respostas_disc' => [
        'disc_1' => 'A',
        'disc_2' => 'B',
        'disc_3' => 'C',
        'disc_4' => 'D',
        'disc_5' => 'A',
        'disc_6' => 'B',
        'disc_7' => 'C',
        'disc_8' => 'A',
        'disc_9' => 'D',
        'disc_10' => 'A'
    ],
    'pontuacao_disc' => [
        'A' => 4,
        'B' => 2,
        'C' => 2,
        'D' => 2,
        'perfil_dominante' => 'A',
        'perfil_secundario' => 'B',
        'perfil_dominante_info' => [
            'cor' => 'Vermelho',
            'tipo' => 'Dominante',
            'foco' => 'Resultado'
        ],
        'perfil_secundario_info' => [
            'cor' => 'Amarelo',
            'tipo' => 'Influente',
            'foco' => 'Prazer e estímulo'
        ]
    ]
];

// Create mock data - Teste das Cores (DISC)
$testeCoresData = [
    'nome' => 'João Teste dos Santos',
    'email' => 'joao.teste@nutridiario.com.br',
    'telefone' => '(11) 91234-5678',
    'cidade_id' => 1, // Assumindo que existe uma cidade com ID 1
    'respostas' => [1, 5, 9, 13, 17, 21, 25, 29, 33, 37] // IDs das opções
];

echo "==========================================\n";
echo "TESTE DE INTEGRAÇÃO COM SUPABASE\n";
echo "==========================================\n\n";

// Test 1: PlanoAlimentarController
echo "📝 TESTE 1: Plano Alimentar\n";
echo "-------------------------------------------\n";

try {
    $controller = new App\Http\Controllers\PlanoAlimentarController(
        new App\Services\SupabaseService()
    );

    // Create request
    $request = Request::create('/planoalimentar', 'POST', $planoAlimentarData);

    echo "Enviando dados para o controller...\n";
    echo "Nome: {$planoAlimentarData['nome_completo']}\n";
    echo "Email: {$planoAlimentarData['email']}\n";
    echo "Tipo: {$planoAlimentarData['tipo']}\n";
    echo "Total de respostas: " . count($planoAlimentarData['respostas_formulario']) . "\n\n";

    $response = $controller->store($request);
    $responseData = json_decode($response->getContent(), true);

    if ($response->getStatusCode() === 201) {
        echo "✅ SUCESSO! Dados salvos no Supabase\n";
        echo "Status Code: " . $response->getStatusCode() . "\n";
        echo "Response: " . json_encode($responseData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    } else {
        echo "❌ ERRO! Status Code: " . $response->getStatusCode() . "\n";
        echo "Response: " . json_encode($responseData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    }

} catch (Exception $e) {
    echo "❌ EXCEÇÃO: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n==========================================\n";
echo "TESTE 2: Verificar dados no Supabase\n";
echo "==========================================\n\n";

try {
    $supabase = new App\Services\SupabaseService();

    echo "Buscando últimos registros no Supabase...\n\n";
    $allData = $supabase->getAllNutriDiario();

    if ($allData && count($allData) > 0) {
        echo "✅ Encontrados " . count($allData) . " registros\n\n";

        // Show last 3 records
        $lastRecords = array_slice($allData, 0, min(3, count($allData)));

        foreach ($lastRecords as $index => $record) {
            echo "--- Registro " . ($index + 1) . " ---\n";
            echo "ID: " . ($record['id'] ?? 'N/A') . "\n";
            echo "Nome: " . ($record['nome'] ?? 'N/A') . "\n";
            echo "Email: " . ($record['email'] ?? 'N/A') . "\n";
            echo "WhatsApp: " . ($record['whatsapp'] ?? 'N/A') . "\n";
            echo "Data Nascimento: " . ($record['data_nascimentno'] ?? 'N/A') . "\n";
            echo "Tem Dieta: " . ($record['tem_dieta'] ?? 'N/A') . "\n";

            if (isset($record['respostas_forms'])) {
                $forms = is_string($record['respostas_forms'])
                    ? json_decode($record['respostas_forms'], true)
                    : $record['respostas_forms'];

                echo "Tipo: " . ($forms['tipo'] ?? 'N/A') . "\n";

                if (isset($forms['respostas_formulario'])) {
                    $totalRespostas = count($forms['respostas_formulario']);
                    echo "Total Respostas Formulário: $totalRespostas\n";

                    // Show first 2 questions
                    $count = 0;
                    foreach ($forms['respostas_formulario'] as $key => $resposta) {
                        if ($count >= 2) break;

                        if (is_array($resposta) && isset($resposta['pergunta'])) {
                            echo "  → $key: {$resposta['pergunta']}\n";
                            echo "     Resposta: " . ($resposta['resposta'] ?? $resposta['valor'] ?? 'N/A') . "\n";
                        }
                        $count++;
                    }
                    if ($totalRespostas > 2) {
                        echo "  ... e mais " . ($totalRespostas - 2) . " respostas\n";
                    }
                }

                if (isset($forms['DISC'])) {
                    echo "DISC Respostas: " . (isset($forms['DISC']['respostas']) ? count($forms['DISC']['respostas']) : 0) . "\n";
                    if (isset($forms['DISC']['pontuacao']['perfil_dominante'])) {
                        echo "Perfil Dominante: " . $forms['DISC']['pontuacao']['perfil_dominante'] . "\n";
                    }
                }
            }

            echo "Criado em: " . ($record['created_at'] ?? 'N/A') . "\n";
            echo "\n";
        }
    } else {
        echo "⚠️  Nenhum registro encontrado\n";
    }

} catch (Exception $e) {
    echo "❌ ERRO ao buscar dados: " . $e->getMessage() . "\n";
}

echo "\n==========================================\n";
echo "TESTE CONCLUÍDO\n";
echo "==========================================\n";
