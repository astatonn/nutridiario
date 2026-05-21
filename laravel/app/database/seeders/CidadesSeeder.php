<?php

namespace Database\Seeders;

use App\Models\Cidade;
use App\Models\Estado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cidades = [
            // São Paulo
            ['nome' => 'São Paulo', 'uf' => 'SP'],
            ['nome' => 'Campinas', 'uf' => 'SP'],
            ['nome' => 'Santos', 'uf' => 'SP'],
            ['nome' => 'Ribeirão Preto', 'uf' => 'SP'],
            ['nome' => 'Sorocaba', 'uf' => 'SP'],

            // Rio de Janeiro
            ['nome' => 'Rio de Janeiro', 'uf' => 'RJ'],
            ['nome' => 'Niterói', 'uf' => 'RJ'],
            ['nome' => 'Petrópolis', 'uf' => 'RJ'],

            // Minas Gerais
            ['nome' => 'Belo Horizonte', 'uf' => 'MG'],
            ['nome' => 'Uberlândia', 'uf' => 'MG'],
            ['nome' => 'Juiz de Fora', 'uf' => 'MG'],

            // Bahia
            ['nome' => 'Salvador', 'uf' => 'BA'],
            ['nome' => 'Feira de Santana', 'uf' => 'BA'],

            // Paraná
            ['nome' => 'Curitiba', 'uf' => 'PR'],
            ['nome' => 'Londrina', 'uf' => 'PR'],
            ['nome' => 'Maringá', 'uf' => 'PR'],

            // Rio Grande do Sul
            ['nome' => 'Porto Alegre', 'uf' => 'RS'],
            ['nome' => 'Caxias do Sul', 'uf' => 'RS'],

            // Santa Catarina
            ['nome' => 'Florianópolis', 'uf' => 'SC'],
            ['nome' => 'Joinville', 'uf' => 'SC'],

            // Pernambuco
            ['nome' => 'Recife', 'uf' => 'PE'],

            // Ceará
            ['nome' => 'Fortaleza', 'uf' => 'CE'],

            // Distrito Federal
            ['nome' => 'Brasília', 'uf' => 'DF'],

            // Goiás
            ['nome' => 'Goiânia', 'uf' => 'GO'],

            // Amazonas
            ['nome' => 'Manaus', 'uf' => 'AM'],

            // Pará
            ['nome' => 'Belém', 'uf' => 'PA'],
        ];

        foreach ($cidades as $cidadeData) {
            $estado = Estado::where('uf', $cidadeData['uf'])->first();
            if ($estado) {
                Cidade::create([
                    'nome' => $cidadeData['nome'],
                    'estado_id' => $estado->id,
                ]);
            }
        }
    }
}
