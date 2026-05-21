<?php

namespace App\Console\Commands;

use App\Models\Estado;
use App\Models\Cidade;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ImportCidadesIBGE extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ibge:import-cidades';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importa todas as cidades do Brasil via API do IBGE';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Buscando municípios do IBGE...');

        try {
            // Busca todos os municípios do Brasil
            $response = Http::timeout(30)->get('https://servicodados.ibge.gov.br/api/v1/localidades/municipios');

            if (!$response->successful()) {
                $this->error('Erro ao buscar dados do IBGE');
                return 1;
            }

            $municipios = $response->json();
            $this->info('Total de municípios encontrados: ' . count($municipios));

            // Limpa a tabela de cidades antes de importar
            $this->info('Limpando tabela de cidades...');
            DB::table('cidades')->delete();

            $this->info('Importando municípios...');
            $bar = $this->output->createProgressBar(count($municipios));

            $imported = 0;
            $errors = 0;

            foreach ($municipios as $municipio) {
                try {
                    // Busca o estado pela UF
                    $uf = $municipio['microrregiao']['mesorregiao']['UF']['sigla'];
                    $estado = Estado::where('uf', $uf)->first();

                    if (!$estado) {
                        $this->warn("Estado não encontrado para UF: {$uf}");
                        $errors++;
                        continue;
                    }

                    // Cria a cidade
                    Cidade::create([
                        'nome' => $municipio['nome'],
                        'estado_id' => $estado->id,
                    ]);

                    $imported++;
                } catch (\Exception $e) {
                    $this->error("Erro ao importar {$municipio['nome']}: " . $e->getMessage());
                    $errors++;
                }

                $bar->advance();
            }

            $bar->finish();
            $this->newLine(2);
            $this->info("Importação concluída!");
            $this->info("Municípios importados: {$imported}");

            if ($errors > 0) {
                $this->warn("Erros durante importação: {$errors}");
            }

            // Mostra estatísticas por estado
            $this->info("\nEstatísticas por estado:");
            $stats = DB::table('cidades')
                ->join('estados', 'cidades.estado_id', '=', 'estados.id')
                ->select('estados.uf', 'estados.nome', DB::raw('COUNT(cidades.id) as total'))
                ->groupBy('estados.id', 'estados.uf', 'estados.nome')
                ->orderBy('estados.uf')
                ->get();

            foreach ($stats as $stat) {
                $this->line("{$stat->uf} - {$stat->nome}: {$stat->total} municípios");
            }

            return 0;

        } catch (\Exception $e) {
            $this->error('Erro: ' . $e->getMessage());
            return 1;
        }
    }
}
