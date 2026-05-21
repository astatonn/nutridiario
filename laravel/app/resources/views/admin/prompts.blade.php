@extends('layouts.admin')

@section('title', 'Configurar Prompts IA')
@section('page-title', 'Configurar Prompts de IA')

@section('content')
<div class="content-card">
    <p style="color: #666; margin-bottom: 25px;">
        Configure os prompts utilizados para gerar análises comportamentais e planos alimentares com Inteligência Artificial.
    </p>

    <form method="POST" action="{{ route('admin.prompts.update') }}">
        @csrf

        <div style="margin-bottom: 30px;">
            <label style="display: block; font-weight: 600; margin-bottom: 10px; color: #2c3e50; font-size: 16px;">
                📊 Prompt para Diagnóstico (Teste das Cores)
            </label>
            <textarea
                name="diagnostico_system"
                required
                rows="15"
                style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; font-family: monospace; line-height: 1.6;"
            >{{ $prompts['diagnostico']['system'] ?? '' }}</textarea>
            <small style="color: #666; display: block; margin-top: 8px;">
                Este prompt é usado para gerar análises comportamentais do teste de cores/DISC.
            </small>
        </div>

        <div style="margin-bottom: 30px;">
            <label style="display: block; font-weight: 600; margin-bottom: 10px; color: #2c3e50; font-size: 16px;">
                🥗 Prompt para Plano Alimentar
            </label>
            <textarea
                name="plano_alimentar_system"
                required
                rows="20"
                style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; font-family: monospace; line-height: 1.6;"
            >{{ $prompts['plano_alimentar']['system'] ?? '' }}</textarea>
            <small style="color: #666; display: block; margin-top: 8px;">
                Este prompt é usado para gerar planos alimentares comportamentais completos.
            </small>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn">
                Salvar Alterações
            </button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
