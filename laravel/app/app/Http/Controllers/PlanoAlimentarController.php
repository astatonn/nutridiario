<?php

namespace App\Http\Controllers;

use App\Services\SupabaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlanoAlimentarController extends Controller
{
    protected SupabaseService $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    /**
     * Display the plano alimentar form
     */
    public function index()
    {
        return view('adietaquefunciona');
    }

    /**
     * Store plano alimentar responses
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'tipo' => 'nullable|string',
                'nome_completo' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'whatsapp' => 'required|string|max:20',
                'data_nascimento' => 'required|string',
                'respostas_formulario' => 'nullable|array',
                'respostas_disc' => 'nullable|array',
                'pontuacao_disc' => 'nullable|array',
                'fluxo' => 'nullable|string',
            ]);

            // Since we removed the initial "tem dieta?" question,
            // all users follow the "não tem dieta" flow
            $temDieta = 'nao';

            // Prepare data for Supabase - ALL form data in respostas_forms JSON column
            $supabaseData = [
                'nome' => $validated['nome_completo'],
                'email' => $validated['email'],
                'whatsapp' => $validated['whatsapp'],
                'data_nascimentno' => $validated['data_nascimento'], // Note: typo in column name
                'tem_dieta' => $temDieta,
                'respostas_forms' => [
                    'tipo' => $validated['tipo'] ?? 'plano_alimentar',
                    'fluxo' => $validated['fluxo'] ?? null,
                    'data_envio' => now()->toISOString(),
                    // All form responses in one place
                    'respostas_formulario' => $validated['respostas_formulario'] ?? [],
                    // DISC responses and scoring
                    'DISC' => [
                        'respostas' => $validated['respostas_disc'] ?? [],
                        'pontuacao' => $validated['pontuacao_disc'] ?? [],
                    ],
                ],
            ];

            // Save to Supabase
            $result = $this->supabase->insertNutriDiario($supabaseData);

            if ($result) {
                Log::info('Plano alimentar saved successfully', [
                    'nome' => $validated['nome_completo'],
                    'email' => $validated['email'],
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Dados salvos com sucesso!',
                    'data' => $result,
                ], 201);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erro ao salvar dados no Supabase',
            ], 500);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in plano alimentar', [
                'errors' => $e->errors(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error storing plano alimentar', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar dados: ' . $e->getMessage(),
            ], 500);
        }
    }
}
