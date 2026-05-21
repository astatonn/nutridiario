<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use App\Models\Estado;
use App\Models\TesteCoresPergunta;
use App\Models\TesteCoresResposta;
use App\Models\TesteCoresOpcao;
use App\Services\SupabaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TesteCoresController extends Controller
{
    protected SupabaseService $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }
    /**
     * Display the test form.
     */
    public function index()
    {
        $perguntas = TesteCoresPergunta::with('opcoes')
            ->orderBy('ordem')
            ->get();

        $estados = Estado::orderBy('nome')->get();

        return view('teste-cores.index', compact('perguntas', 'estados'));
    }

    /**
     * Get cities for a given state.
     */
    public function getCidades($estadoId)
    {
        $cidades = Cidade::where('estado_id', $estadoId)
            ->orderBy('nome')
            ->get();

        return response()->json($cidades);
    }

    /**
     * Store the test responses.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefone' => 'required|string|max:20',
            'cidade_id' => 'required|exists:cidades,id',
            'respostas' => 'required|array|size:10',
            'respostas.*' => 'required|exists:teste_cores_opcoes,id',
        ]);

        // Generate a unique session ID for this test
        $sessionId = Str::uuid();

        // Prepare DISC answers with questions and colors
        $respostasDISC = [];
        foreach ($validated['respostas'] as $index => $opcaoId) {
            $opcao = TesteCoresOpcao::with('pergunta')->find($opcaoId);
            $respostasDISC[] = [
                'pergunta_numero' => $index + 1,
                'pergunta_texto' => $opcao->pergunta->texto,
                'opcao_letra' => $opcao->letra,
                'opcao_texto' => $opcao->texto,
                'cor' => $opcao->cor,
            ];
        }

        // Count colors for profile
        $coresCount = collect($respostasDISC)->countBy('cor')->toArray();
        $corDominante = collect($coresCount)->sortDesc()->keys()->first();

        // Prepare data for Supabase
        $supabaseData = [
            'nome' => $validated['nome'],
            'email' => $validated['email'],
            'whatsapp' => $validated['telefone'],
            'data_nascimentno' => null, // Not collected in this form
            'tem_dieta' => null, // Not collected in this form
            'respostas_forms' => [
                'tipo' => 'DISC',
                'session_id' => $sessionId,
                'respostas' => $respostasDISC,
                'resultado' => [
                    'cores_count' => $coresCount,
                    'cor_dominante' => $corDominante,
                ],
            ],
        ];

        // Save to Supabase
        $this->supabase->insertNutriDiario($supabaseData);

        // Save each answer to local database (for existing functionality)
        foreach ($validated['respostas'] as $opcaoId) {
            TesteCoresResposta::create([
                'nome' => $validated['nome'],
                'email' => $validated['email'],
                'telefone' => $validated['telefone'],
                'cidade_id' => $validated['cidade_id'],
                'opcao_id' => $opcaoId,
                'session_id' => $sessionId,
            ]);
        }

        return redirect()->route('teste-cores.resultado', ['session' => $sessionId])
            ->with('success', 'Teste concluído com sucesso!');
    }

    /**
     * Show the result page.
     */
    public function resultado($sessionId)
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

        return view('teste-cores.resultado', compact('respostas', 'cores', 'corDominante', 'perfil'));
    }
}
