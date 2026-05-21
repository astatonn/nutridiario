<?php

namespace App\Http\Controllers;

use App\Models\TesteCoresResposta;
use App\Models\TesteCoresAnalise;
use App\Models\FormularioGeralAnalise;
use App\Models\FormularioGeralDeleted;
use App\Services\SupabaseService;
use App\Services\OpenAIService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected SupabaseService $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }
    /**
     * Display admin dashboard with metrics.
     */
    public function dashboard()
    {
        // Diagnóstico (Teste das Cores) - Local Database
        $diagnostico = [
            'total' => TesteCoresResposta::distinct('session_id')->count('session_id'),
            'ultimas24h' => TesteCoresResposta::where('created_at', '>=', now()->subDay())
                ->distinct('session_id')
                ->count('session_id'),
            'ultimos7dias' => TesteCoresResposta::where('created_at', '>=', now()->subDays(7))
                ->distinct('session_id')
                ->count('session_id'),
        ];

        // Plano Alimentar - Supabase
        $planoAlimentar = [
            'total' => $this->supabase->getCountByDateRange(),
            'ultimas24h' => $this->supabase->getCountByDateRange(now()->subDay()->toISOString()),
            'ultimos7dias' => $this->supabase->getCountByDateRange(now()->subDays(7)->toISOString()),
        ];

        return view('admin.dashboard', compact('diagnostico', 'planoAlimentar'));
    }

    /**
     * Display all test responses.
     */
    public function index()
    {
        $respostas = TesteCoresResposta::with(['opcao.pergunta', 'cidade.estado'])
            ->get()
            ->groupBy('session_id')
            ->sortByDesc(function ($group) {
                // Sort groups by the most recent created_at within each session
                return $group->max('created_at');
            });

        return view('admin.respostas', compact('respostas'));
    }

    /**
     * Show detailed results for a specific test session.
     */
    public function show($sessionId)
    {
        $respostas = TesteCoresResposta::where('session_id', $sessionId)
            ->with(['opcao.pergunta', 'cidade.estado'])
            ->get();

        if ($respostas->isEmpty()) {
            abort(404, 'Teste não encontrado.');
        }

        // Count colors
        $cores = $respostas->pluck('opcao.cor')->countBy();

        // Determine dominant color
        $corDominante = $cores->sortDesc()->keys()->first();

        // Color profile descriptions
        $perfis = [
            'vermelho' => [
                'nome' => 'Perfil Vermelho - Disciplinado',
                'descricao' => 'Você é focado em resultados, disciplinado e gosta de seguir regras e estruturas. Busca eficiência e controle em sua alimentação.',
            ],
            'amarelo' => [
                'nome' => 'Perfil Amarelo - Sociável',
                'descricao' => 'Você valoriza experiências sociais, prazer e conexões. A alimentação para você é um momento de alegria e compartilhamento.',
            ],
            'verde' => [
                'nome' => 'Perfil Verde - Analítico',
                'descricao' => 'Você é cuidadoso, busca informações e gosta de entender profundamente. Prioriza conhecimento e ciência na alimentação.',
            ],
            'azul' => [
                'nome' => 'Perfil Azul - Livre',
                'descricao' => 'Você valoriza autonomia, flexibilidade e liberdade. Prefere adaptar as regras ao seu estilo pessoal.',
            ],
        ];

        $perfil = $perfis[$corDominante];

        // Get existing analysis if available
        $analise = TesteCoresAnalise::where('session_id', $sessionId)->first();

        return view('admin.detalhes', compact('respostas', 'cores', 'corDominante', 'perfil', 'analise'));
    }

    /**
     * Generate AI analysis for a test session
     */
    public function generateAnalysis(Request $request, $sessionId, OpenAIService $openai)
    {
        $respostas = TesteCoresResposta::where('session_id', $sessionId)
            ->with(['opcao.pergunta', 'cidade.estado'])
            ->get();

        if ($respostas->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Teste não encontrado.',
            ], 404);
        }

        // Count colors
        $cores = $respostas->pluck('opcao.cor')->countBy();
        $corDominante = $cores->sortDesc()->keys()->first();

        // Color profile descriptions
        $perfis = [
            'vermelho' => [
                'nome' => 'Perfil Vermelho - Disciplinado',
                'descricao' => 'Você é focado em resultados, disciplinado e gosta de seguir regras e estruturas. Busca eficiência e controle em sua alimentação.',
            ],
            'amarelo' => [
                'nome' => 'Perfil Amarelo - Sociável',
                'descricao' => 'Você valoriza experiências sociais, prazer e conexões. A alimentação para você é um momento de alegria e compartilhamento.',
            ],
            'verde' => [
                'nome' => 'Perfil Verde - Analítico',
                'descricao' => 'Você é cuidadoso, busca informações e gosta de entender profundamente. Prioriza conhecimento e ciência na alimentação.',
            ],
            'azul' => [
                'nome' => 'Perfil Azul - Livre',
                'descricao' => 'Você valoriza autonomia, flexibilidade e liberdade. Prefere adaptar as regras ao seu estilo pessoal.',
            ],
        ];

        $perfil = $perfis[$corDominante];

        // Generate analysis
        $analiseTexto = $openai->generateAnalysis($respostas, $perfil);

        if (!$analiseTexto) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao gerar análise. Por favor, tente novamente.',
            ], 500);
        }

        // Save or update analysis
        $analise = TesteCoresAnalise::updateOrCreate(
            ['session_id' => $sessionId],
            ['analise' => $analiseTexto]
        );

        return response()->json([
            'success' => true,
            'message' => 'Análise gerada com sucesso!',
            'analise' => $analiseTexto,
        ]);
    }

    /**
     * Test Supabase connection and retrieve data from nutridiario table
     */
    public function testeSupabase()
    {
        $dados = $this->supabase->getAllNutriDiario();

        if ($dados === null) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao conectar com o Supabase',
                'dados' => null
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Conexão com Supabase bem-sucedida',
            'total_registros' => count($dados),
            'dados' => $dados
        ]);
    }

    /**
     * Display nutrition questionnaire responses from Supabase
     */
    public function formularioGeral()
    {
        $dados = $this->supabase->getAllNutriDiario();

        // Get deleted IDs and filter them out
        $deletedIds = FormularioGeralDeleted::pluck('registro_id')->toArray();
        $dados = collect($dados)->filter(function($item) use ($deletedIds) {
            return !in_array($item['id'], $deletedIds);
        })->values()->toArray();

        // Get existing analyses
        $analises = FormularioGeralAnalise::all()->keyBy('registro_id');

        return view('admin.formulario-geral', compact('dados', 'analises'));
    }

    /**
     * Generate AI analysis for a formulario geral submission
     */
    public function generateFormularioGeralAnalysis(Request $request, $id, OpenAIService $openai)
    {
        // Get the record from Supabase
        $dados = $this->supabase->getNutriDiarioById($id);

        if (!$dados) {
            return response()->json([
                'success' => false,
                'message' => 'Registro não encontrado.',
            ], 404);
        }

        // Generate analysis
        $analiseTexto = $openai->generatePlanoAlimentarAnalysis($dados);

        if (!$analiseTexto) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao gerar análise. Por favor, tente novamente.',
            ], 500);
        }

        // Save or update analysis
        $analise = FormularioGeralAnalise::updateOrCreate(
            ['registro_id' => $id],
            ['analise' => $analiseTexto]
        );

        return response()->json([
            'success' => true,
            'message' => 'Análise gerada com sucesso!',
            'analise' => $analiseTexto,
        ]);
    }

    /**
     * Show prompts configuration page
     */
    public function editPrompts()
    {
        $prompts = config('prompts');
        return view('admin.prompts', compact('prompts'));
    }

    /**
     * Update prompts configuration
     */
    public function updatePrompts(Request $request)
    {
        $validated = $request->validate([
            'diagnostico_system' => 'required|string',
            'plano_alimentar_system' => 'required|string',
        ]);

        $configPath = config_path('prompts.php');
        $configContent = "<?php\n\nreturn [\n";
        $configContent .= "    'diagnostico' => [\n";
        $configContent .= "        'system' => " . var_export($validated['diagnostico_system'], true) . ",\n";
        $configContent .= "    ],\n\n";
        $configContent .= "    'plano_alimentar' => [\n";
        $configContent .= "        'system' => " . var_export($validated['plano_alimentar_system'], true) . ",\n";
        $configContent .= "    ],\n";
        $configContent .= "];\n";

        file_put_contents($configPath, $configContent);

        // Clear config cache
        \Artisan::call('config:clear');

        return redirect()->route('admin.prompts.edit')->with('success', 'Prompts atualizados com sucesso!');
    }

    /**
     * Show archived records (soft deleted)
     */
    public function arquivados()
    {
        // Get soft deleted diagnostico records
        $diagnosticos = TesteCoresResposta::onlyTrashed()
            ->with(['opcao.pergunta', 'cidade.estado'])
            ->orderBy('deleted_at', 'desc')
            ->get()
            ->groupBy('session_id');

        // Get deleted formulario geral IDs
        $deletedIds = FormularioGeralDeleted::pluck('registro_id')->toArray();

        // Get deleted records from Supabase
        $formulariosGerais = [];
        if (!empty($deletedIds)) {
            $allData = $this->supabase->getAllNutriDiario();
            $formulariosGerais = collect($allData)->filter(function($item) use ($deletedIds) {
                return in_array($item['id'], $deletedIds);
            });
        }

        return view('admin.arquivados', compact('diagnosticos', 'formulariosGerais'));
    }

    /**
     * Soft delete a diagnostico session
     */
    public function deleteDiagnostico($sessionId)
    {
        $respostas = TesteCoresResposta::where('session_id', $sessionId)->get();

        if ($respostas->isEmpty()) {
            return redirect()->back()->with('error', 'Registro não encontrado.');
        }

        // Soft delete all responses for this session
        foreach ($respostas as $resposta) {
            $resposta->delete();
        }

        return redirect()->back()->with('success', 'Registro arquivado com sucesso!');
    }

    /**
     * Restore a soft deleted diagnostico session
     */
    public function restoreDiagnostico($sessionId)
    {
        $respostas = TesteCoresResposta::onlyTrashed()
            ->where('session_id', $sessionId)
            ->get();

        if ($respostas->isEmpty()) {
            return redirect()->back()->with('error', 'Registro não encontrado.');
        }

        // Restore all responses for this session
        foreach ($respostas as $resposta) {
            $resposta->restore();
        }

        return redirect()->back()->with('success', 'Registro restaurado com sucesso!');
    }

    /**
     * Mark a formulario geral as deleted (add to tracking table)
     */
    public function deleteFormularioGeral($id)
    {
        // Check if exists in Supabase
        $registro = $this->supabase->getNutriDiarioById($id);

        if (!$registro) {
            return redirect()->back()->with('error', 'Registro não encontrado.');
        }

        // Add to deleted tracking table
        FormularioGeralDeleted::firstOrCreate(['registro_id' => $id]);

        return redirect()->back()->with('success', 'Registro arquivado com sucesso!');
    }

    /**
     * Restore a formulario geral (remove from tracking table)
     */
    public function restoreFormularioGeral($id)
    {
        $deleted = FormularioGeralDeleted::where('registro_id', $id)->first();

        if (!$deleted) {
            return redirect()->back()->with('error', 'Registro não encontrado.');
        }

        $deleted->delete();

        return redirect()->back()->with('success', 'Registro restaurado com sucesso!');
    }
}
