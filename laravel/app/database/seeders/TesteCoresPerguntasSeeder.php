<?php

namespace Database\Seeders;

use App\Models\TesteCoresPergunta;
use App\Models\TesteCoresOpcao;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TesteCoresPerguntasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $perguntas = [
            [
                'ordem' => 1,
                'texto' => 'Quando decide começar uma dieta, você:',
                'opcoes' => [
                    ['letra' => 'A', 'texto' => 'Quer resultados rápidos e visíveis', 'cor' => 'vermelho'],
                    ['letra' => 'B', 'texto' => 'Fica animado com a novidade', 'cor' => 'amarelo'],
                    ['letra' => 'C', 'texto' => 'Prefere algo simples e duradouro', 'cor' => 'verde'],
                    ['letra' => 'D', 'texto' => 'Quer entender todos os detalhes antes', 'cor' => 'azul'],
                ],
            ],
            [
                'ordem' => 2,
                'texto' => 'Se sai da dieta, o que faz:',
                'opcoes' => [
                    ['letra' => 'A', 'texto' => 'Volta firme no dia seguinte', 'cor' => 'vermelho'],
                    ['letra' => 'B', 'texto' => 'Acha que "tudo bem, depois recomeço"', 'cor' => 'amarelo'],
                    ['letra' => 'C', 'texto' => 'Fica frustrado, mas tenta manter a calma', 'cor' => 'verde'],
                    ['letra' => 'D', 'texto' => 'Analisa o que deu errado e ajusta o plano', 'cor' => 'azul'],
                ],
            ],
            [
                'ordem' => 3,
                'texto' => 'O que mais motiva você a manter a alimentação correta:',
                'opcoes' => [
                    ['letra' => 'A', 'texto' => 'Bater metas e ver resultado rápido', 'cor' => 'vermelho'],
                    ['letra' => 'B', 'texto' => 'Ouvir elogios e se sentir bem socialmente', 'cor' => 'amarelo'],
                    ['letra' => 'C', 'texto' => 'Cuidar da saúde e manter rotina', 'cor' => 'verde'],
                    ['letra' => 'D', 'texto' => 'Saber que está fazendo tudo certo', 'cor' => 'azul'],
                ],
            ],
            [
                'ordem' => 4,
                'texto' => 'Quando o nutricionista muda o plano:',
                'opcoes' => [
                    ['letra' => 'A', 'texto' => 'Aceita se achar que acelera o progresso', 'cor' => 'vermelho'],
                    ['letra' => 'B', 'texto' => 'Gosta da novidade', 'cor' => 'amarelo'],
                    ['letra' => 'C', 'texto' => 'Prefere mudanças pequenas', 'cor' => 'verde'],
                    ['letra' => 'D', 'texto' => 'Questiona e quer entender o motivo', 'cor' => 'azul'],
                ],
            ],
            [
                'ordem' => 5,
                'texto' => 'Seu comportamento em festas ou eventos:',
                'opcoes' => [
                    ['letra' => 'A', 'texto' => 'Come com controle e volta ao plano', 'cor' => 'vermelho'],
                    ['letra' => 'B', 'texto' => 'Gosta de experimentar de tudo', 'cor' => 'amarelo'],
                    ['letra' => 'C', 'texto' => 'Evita exageros, mas participa', 'cor' => 'verde'],
                    ['letra' => 'D', 'texto' => 'Planeja antes o que pode comer', 'cor' => 'azul'],
                ],
            ],
            [
                'ordem' => 6,
                'texto' => 'Quando sente fome fora de hora:',
                'opcoes' => [
                    ['letra' => 'A', 'texto' => 'Segura e espera o horário', 'cor' => 'vermelho'],
                    ['letra' => 'B', 'texto' => 'Belisca algo leve', 'cor' => 'amarelo'],
                    ['letra' => 'C', 'texto' => 'Busca alternativa dentro da dieta', 'cor' => 'verde'],
                    ['letra' => 'D', 'texto' => 'Reavalia horários e ajusta o plano', 'cor' => 'azul'],
                ],
            ],
            [
                'ordem' => 7,
                'texto' => 'Seu prato ideal:',
                'opcoes' => [
                    ['letra' => 'A', 'texto' => 'Simples e funcional', 'cor' => 'vermelho'],
                    ['letra' => 'B', 'texto' => 'Colorido e cheio de sabores', 'cor' => 'amarelo'],
                    ['letra' => 'C', 'texto' => 'Caseiro e equilibrado', 'cor' => 'verde'],
                    ['letra' => 'D', 'texto' => 'Bem calculado e pesado corretamente', 'cor' => 'azul'],
                ],
            ],
            [
                'ordem' => 8,
                'texto' => 'Sua reação diante de uma restrição alimentar:',
                'opcoes' => [
                    ['letra' => 'A', 'texto' => 'Cumpre sem reclamar', 'cor' => 'vermelho'],
                    ['letra' => 'B', 'texto' => 'Tenta driblar de vez em quando', 'cor' => 'amarelo'],
                    ['letra' => 'C', 'texto' => 'Se adapta aos poucos', 'cor' => 'verde'],
                    ['letra' => 'D', 'texto' => 'Quer saber a razão exata', 'cor' => 'azul'],
                ],
            ],
            [
                'ordem' => 9,
                'texto' => 'Como você lida com a rotina alimentar:',
                'opcoes' => [
                    ['letra' => 'A', 'texto' => 'Gosta de metas claras e horários fixos', 'cor' => 'vermelho'],
                    ['letra' => 'B', 'texto' => 'Precisa de variedade e flexibilidade', 'cor' => 'amarelo'],
                    ['letra' => 'C', 'texto' => 'Mantém a disciplina com constância', 'cor' => 'verde'],
                    ['letra' => 'D', 'texto' => 'Prefere tudo organizado e medido', 'cor' => 'azul'],
                ],
            ],
            [
                'ordem' => 10,
                'texto' => 'Quando viaja ou muda de ambiente:',
                'opcoes' => [
                    ['letra' => 'A', 'texto' => 'Cria uma nova estratégia rápida', 'cor' => 'vermelho'],
                    ['letra' => 'B', 'texto' => 'Se adapta com o que tem', 'cor' => 'amarelo'],
                    ['letra' => 'C', 'texto' => 'Tenta manter o máximo possível', 'cor' => 'verde'],
                    ['letra' => 'D', 'texto' => 'Leva tudo planejado', 'cor' => 'azul'],
                ],
            ],
            [
                'ordem' => 11,
                'texto' => 'Sobre acompanhamento nutricional:',
                'opcoes' => [
                    ['letra' => 'A', 'texto' => 'Quer metas e comparativos de evolução', 'cor' => 'vermelho'],
                    ['letra' => 'B', 'texto' => 'Precisa de incentivo e contato próximo', 'cor' => 'amarelo'],
                    ['letra' => 'C', 'texto' => 'Gosta de constância e orientações simples', 'cor' => 'verde'],
                    ['letra' => 'D', 'texto' => 'Quer planilhas, medidas e relatórios técnicos', 'cor' => 'azul'],
                ],
            ],
            [
                'ordem' => 12,
                'texto' => 'O que mais atrapalha sua dieta:',
                'opcoes' => [
                    ['letra' => 'A', 'texto' => 'Falta de resultado rápido', 'cor' => 'vermelho'],
                    ['letra' => 'B', 'texto' => 'Tédio ou falta de motivação', 'cor' => 'amarelo'],
                    ['letra' => 'C', 'texto' => 'Mudanças na rotina', 'cor' => 'verde'],
                    ['letra' => 'D', 'texto' => 'Falta de organização e dados claros', 'cor' => 'azul'],
                ],
            ],
        ];

        foreach ($perguntas as $perguntaData) {
            $pergunta = TesteCoresPergunta::create([
                'ordem' => $perguntaData['ordem'],
                'texto' => $perguntaData['texto'],
            ]);

            foreach ($perguntaData['opcoes'] as $opcaoData) {
                TesteCoresOpcao::create([
                    'pergunta_id' => $pergunta->id,
                    'letra' => $opcaoData['letra'],
                    'texto' => $opcaoData['texto'],
                    'cor' => $opcaoData['cor'],
                ]);
            }
        }
    }
}
