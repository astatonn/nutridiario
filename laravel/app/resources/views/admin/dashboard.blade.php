@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Diagnóstico (Teste das Cores) -->
<div style="margin-bottom: 10px;">
    <h3 style="font-size: 16px; font-weight: 700; color: #2c3e50; margin-bottom: 15px;">📊 Diagnóstico</h3>
</div>
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $diagnostico['total'] }}</div>
        <div class="stat-label">Total de Testes</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $diagnostico['ultimas24h'] }}</div>
        <div class="stat-label">Últimas 24 Horas</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $diagnostico['ultimos7dias'] }}</div>
        <div class="stat-label">Últimos 7 Dias</div>
    </div>
</div>

<!-- Plano Alimentar -->
<div style="margin-bottom: 10px; margin-top: 30px;">
    <h3 style="font-size: 16px; font-weight: 700; color: #2c3e50; margin-bottom: 15px;">🥗 Plano Alimentar</h3>
</div>
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $planoAlimentar['total'] }}</div>
        <div class="stat-label">Total de Formulários</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $planoAlimentar['ultimas24h'] }}</div>
        <div class="stat-label">Últimas 24 Horas</div>
    </div>
    <div class="stat-card">
        <div class="stat-value">{{ $planoAlimentar['ultimos7dias'] }}</div>
        <div class="stat-label">Últimos 7 Dias</div>
    </div>
</div>

<div class="content-card">
    <h2 style="font-size: 20px; font-weight: 700; margin-bottom: 20px; color: #2c3e50;">Alterar Senha</h2>

    <form method="POST" action="{{ route('user.change-password') }}" style="max-width: 500px;">
        @csrf

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #2c3e50; font-size: 14px;">Senha Atual</label>
            <input
                type="password"
                name="current_password"
                required
                style="width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; transition: border-color 0.3s;"
                onfocus="this.style.borderColor='#3498db'"
                onblur="this.style.borderColor='#ddd'"
            >
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #2c3e50; font-size: 14px;">Nova Senha</label>
            <input
                type="password"
                name="new_password"
                required
                style="width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; transition: border-color 0.3s;"
                onfocus="this.style.borderColor='#3498db'"
                onblur="this.style.borderColor='#ddd'"
            >
        </div>

        <div style="margin-bottom: 25px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #2c3e50; font-size: 14px;">Confirmar Nova Senha</label>
            <input
                type="password"
                name="new_password_confirmation"
                required
                style="width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; transition: border-color 0.3s;"
                onfocus="this.style.borderColor='#3498db'"
                onblur="this.style.borderColor='#ddd'"
            >
        </div>

        <button type="submit" class="btn">
            Alterar Senha
        </button>
    </form>
</div>
@endsection
