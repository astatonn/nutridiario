@extends('layouts.admin')

@section('title', 'Registros Arquivados')
@section('page-title', 'Registros Arquivados')

@push('styles')
<style>
    /* Responsividade das tabelas */
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
<!-- Diagnóstico Section -->
<div class="content-card" style="margin-bottom: 25px;">
    <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 20px; color: #2c3e50;">
        🎨 Diagnóstico (Teste das Cores)
    </h2>

    @if($diagnosticos->count() > 0)
        <div class="table-responsive">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #3498db; color: white;">
                    <tr>
                        <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">Data Arquivamento</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">Nome</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">E-mail</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">Telefone</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">Cidade/UF</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">Ações</th>
                    </tr>
                </thead>
            <tbody>
                @foreach($diagnosticos as $sessionId => $respostaGroup)
                    @php
                        $firstResposta = $respostaGroup->first();
                    @endphp
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 15px; font-size: 14px; color: #333;">{{ $firstResposta->deleted_at->format('d/m/Y H:i') }}</td>
                        <td style="padding: 15px; font-size: 14px; color: #333;">{{ $firstResposta->nome }}</td>
                        <td style="padding: 15px; font-size: 14px; color: #333;">{{ $firstResposta->email }}</td>
                        <td style="padding: 15px; font-size: 14px; color: #333;">{{ $firstResposta->telefone }}</td>
                        <td style="padding: 15px; font-size: 14px; color: #333;">{{ $firstResposta->cidade->nome }}/{{ $firstResposta->cidade->estado->uf }}</td>
                        <td style="padding: 15px;">
                            <form method="POST" action="{{ route('admin.diagnostico.restore', $sessionId) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm" style="background: #28a745;" onclick="return confirm('Restaurar este registro?')">
                                    Restaurar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @else
        <div style="padding: 40px 20px; text-align: center; color: #999; background: #f8f9fa; border-radius: 8px;">
            <p>Nenhum registro de diagnóstico arquivado.</p>
        </div>
    @endif
</div>

<!-- Formulário Geral Section -->
<div class="content-card">
    <h2 style="font-size: 18px; font-weight: 700; margin-bottom: 20px; color: #2c3e50;">
        📋 Formulário Geral (Plano Alimentar)
    </h2>

    @if($formulariosGerais && count($formulariosGerais) > 0)
        <div class="table-responsive">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #3498db; color: white;">
                    <tr>
                        <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">Data</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">Nome</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">E-mail</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">WhatsApp</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">Ações</th>
                    </tr>
                </thead>
            <tbody>
                @foreach($formulariosGerais as $registro)
                    @php
                        $createdAt = isset($registro['created_at'])
                            ? \Carbon\Carbon::parse($registro['created_at'])->format('d/m/Y H:i')
                            : 'N/A';
                    @endphp
                    <tr style="border-bottom: 1px solid #f0f0f0;">
                        <td style="padding: 15px; font-size: 14px; color: #333;">{{ $createdAt }}</td>
                        <td style="padding: 15px; font-size: 14px; color: #333;">{{ $registro['nome'] ?? 'N/A' }}</td>
                        <td style="padding: 15px; font-size: 14px; color: #333;">{{ $registro['email'] ?? 'N/A' }}</td>
                        <td style="padding: 15px; font-size: 14px; color: #333;">{{ $registro['whatsapp'] ?? 'N/A' }}</td>
                        <td style="padding: 15px;">
                            <form method="POST" action="{{ route('admin.formulario-geral.restore', $registro['id']) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm" style="background: #28a745;" onclick="return confirm('Restaurar este registro?')">
                                    Restaurar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @else
        <div style="padding: 40px 20px; text-align: center; color: #999; background: #f8f9fa; border-radius: 8px;">
            <p>Nenhum formulário geral arquivado.</p>
        </div>
    @endif
</div>
@endsection
