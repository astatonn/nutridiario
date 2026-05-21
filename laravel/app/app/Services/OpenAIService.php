<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService
{
    protected string $apiKey;
    protected string $model = 'gpt-4o-mini';

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
    }

    /**
     * Generate analysis for test responses
     */
    public function generateAnalysis($respostas, array $perfil): ?string
    {
        try {
            // Build the prompt with test data
            $prompt = $this->buildPrompt($respostas, $perfil);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post('https://api.openai.com/v1/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $this->getSystemPrompt()
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 2000,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? null;
            }

            Log::error('OpenAI API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('OpenAI API exception', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Generate analysis for general form (plano alimentar)
     */
    public function generatePlanoAlimentarAnalysis(array $dados): ?string
    {
        try {
            $prompt = $this->buildPlanoAlimentarPrompt($dados);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(90)->post('https://api.openai.com/v1/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => config('prompts.plano_alimentar.system')
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 3000,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? null;
            }

            Log::error('OpenAI API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('OpenAI API exception', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Get the system prompt
     */
    protected function getSystemPrompt(): string
    {
        return config('prompts.diagnostico.system');
    }

    /**
     * Build the prompt with test data
     */
    protected function buildPrompt($respostas, array $perfil): string
    {
        $prompt = "Analise o seguinte teste de perfil comportamental:\n\n";
        $prompt .= "PERFIL DOMINANTE: {$perfil['nome']}\n";
        $prompt .= "DESCRIÇÃO: {$perfil['descricao']}\n\n";
        $prompt .= "RESPOSTAS DO TESTE:\n";

        foreach ($respostas as $resposta) {
            $prompt .= "• {$resposta->opcao->pergunta->texto}\n";
            $prompt .= "  Resposta: {$resposta->opcao->texto} (Cor: {$resposta->opcao->cor})\n\n";
        }

        return $prompt;
    }

    /**
     * Build the prompt for plano alimentar
     */
    protected function buildPlanoAlimentarPrompt(array $dados): string
    {
        $prompt = "Analise o seguinte formulário nutricional e crie um plano alimentar comportamental completo:\n\n";

        $prompt .= "INFORMAÇÕES DO PACIENTE:\n";
        $prompt .= "Nome: " . ($dados['nome'] ?? 'N/A') . "\n";
        $prompt .= "E-mail: " . ($dados['email'] ?? 'N/A') . "\n";
        $prompt .= "WhatsApp: " . ($dados['whatsapp'] ?? 'N/A') . "\n";
        $prompt .= "Data de Nascimento: " . ($dados['data_nascimentno'] ?? 'N/A') . "\n";
        $prompt .= "Tem dieta atual: " . ($dados['tem_dieta'] ?? 'Não informado') . "\n\n";

        if (isset($dados['respostas_forms']) && is_array($dados['respostas_forms'])) {
            $respostasForms = $dados['respostas_forms'];

            $prompt .= "RESPOSTAS DO FORMULÁRIO:\n";
            if (isset($respostasForms['respostas_formulario']) && is_array($respostasForms['respostas_formulario'])) {
                foreach ($respostasForms['respostas_formulario'] as $key => $resposta) {
                    if (is_array($resposta)) {
                        $valor = $resposta['valor'] ?? $resposta['resposta'] ?? json_encode($resposta);
                    } else {
                        $valor = $resposta;
                    }
                    // Convert array to string if needed
                    if (is_array($valor)) {
                        $valor = json_encode($valor, JSON_UNESCAPED_UNICODE);
                    }
                    $prompt .= "• {$key}: {$valor}\n";
                }
            }

            $prompt .= "\nRESPOSTAS DISC:\n";
            if (isset($respostasForms['DISC']['respostas']) && is_array($respostasForms['DISC']['respostas'])) {
                foreach ($respostasForms['DISC']['respostas'] as $key => $resposta) {
                    $respostaStr = is_array($resposta) ? json_encode($resposta, JSON_UNESCAPED_UNICODE) : $resposta;
                    $prompt .= "• {$key}: {$respostaStr}\n";
                }
            }

            $prompt .= "\nPONTUAÇÃO DISC:\n";
            if (isset($respostasForms['DISC']['pontuacao']) && is_array($respostasForms['DISC']['pontuacao'])) {
                foreach ($respostasForms['DISC']['pontuacao'] as $cor => $pontos) {
                    $pontosStr = is_array($pontos) ? json_encode($pontos, JSON_UNESCAPED_UNICODE) : $pontos;
                    $prompt .= "• {$cor}: {$pontosStr}\n";
                }
            }
        }

        return $prompt;
    }
}
