@extends('layouts.admin')

@section('title', 'A Dieta Que Funciona - Respostas')
@section('page-title', 'A Dieta Que Funciona')

@push('styles')
<style>
    /* Responsividade da tabela */
    @media (max-width: 1024px) {
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            min-width: 800px;
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        table {
            font-size: 12px;
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
    }
</style>
@endpush

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $respostas->count() }}</div>
        <div class="stat-label">Total de Testes Realizados</div>
    </div>
</div>

<div class="content-card">
    @if($respostas->count() > 0)
        <div class="table-responsive">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #3498db; color: white;">
                    <tr>
                        <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">Data</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">Nome</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">E-mail</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">Telefone</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">Cidade/UF</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">Perfil Dominante</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">Ações</th>
                    </tr>
                </thead>
            <tbody>
                @foreach($respostas as $sessionId => $respostaGroup)
                    @php
                        $firstResposta = $respostaGroup->first();
                        $cores = $respostaGroup->pluck('opcao.cor')->countBy();
                        $corDominante = $cores->sortDesc()->keys()->first();
                        $perfilNomes = [
                            'vermelho' => 'Disciplinado',
                            'amarelo' => 'Sociável',
                            'verde' => 'Analítico',
                            'azul' => 'Livre'
                        ];
                        $badgeColors = [
                            'vermelho' => 'background: #ffe5e5; color: #dc3545; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;',
                            'amarelo' => 'background: #fff8e1; color: #ff9800; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;',
                            'verde' => 'background: #e8f5e9; color: #28a745; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;',
                            'azul' => 'background: #e3f2fd; color: #007bff; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;'
                        ];
                    @endphp
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 15px; font-size: 14px; color: #333;">{{ $firstResposta->created_at->format('d/m/Y H:i') }}</td>
                        <td style="padding: 15px; font-size: 14px; color: #333;">{{ $firstResposta->nome }}</td>
                        <td style="padding: 15px; font-size: 14px; color: #333;">{{ $firstResposta->email }}</td>
                        <td style="padding: 15px; font-size: 14px; color: #333;">{{ $firstResposta->telefone }}</td>
                        <td style="padding: 15px; font-size: 14px; color: #333;">{{ $firstResposta->cidade->nome }}/{{ $firstResposta->cidade->estado->uf }}</td>
                        <td style="padding: 15px; font-size: 14px;">
                            <span style="{{ $badgeColors[$corDominante] }}">
                                {{ $perfilNomes[$corDominante] }}
                            </span>
                        </td>
                        <td style="padding: 15px;">
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('admin.respostas.show', $sessionId) }}" class="btn btn-sm">Ver Detalhes</a>
                                <form method="POST" action="{{ route('admin.diagnostico.delete', $sessionId) }}" style="display: inline;">
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
            <p>Nenhum teste foi realizado ainda.</p>
        </div>
    @endif
</div>
@endsection
