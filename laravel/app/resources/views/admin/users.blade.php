@extends('layouts.admin')

@section('title', 'Gerenciar Usuários')
@section('page-title', 'Gerenciar Usuários')

@push('styles')
<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content-custom {
        background: white;
        padding: 30px;
        border-radius: 10px;
        max-width: 500px;
        width: 90%;
        box-shadow: 0 5px 30px rgba(0, 0, 0, 0.3);
    }
</style>
@endpush

@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-value">{{ $users->total() }}</div>
        <div class="stat-label">Total de Usuários</div>
    </div>
</div>

<div class="content-card">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #3498db; color: white;">
                <tr>
                    <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">ID</th>
                    <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">Nome</th>
                    <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">E-mail</th>
                    <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">Telefone</th>
                    <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">Tipo</th>
                    <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">Cadastrado em</th>
                    <th style="padding: 15px; text-align: left; font-weight: 600; font-size: 14px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr style="border-bottom: 1px solid #f0f0f0;">
                    <td style="padding: 15px; font-size: 14px; color: #333;">{{ $user->id }}</td>
                    <td style="padding: 15px; font-size: 14px; color: #333; font-weight: 600;">{{ $user->name }}</td>
                    <td style="padding: 15px; font-size: 14px; color: #333;">{{ $user->email }}</td>
                    <td style="padding: 15px; font-size: 14px; color: #333;">{{ $user->telefone ?? '-' }}</td>
                    <td style="padding: 15px;">
                        @if($user->is_admin)
                            <span style="background: #d4edda; border-left: 4px solid #28a745; color: #155724; padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: 600;">ADMIN</span>
                        @else
                            <span style="background: #e3f2fd; border-left: 4px solid #007bff; color: #004085; padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: 600;">USUÁRIO</span>
                        @endif
                    </td>
                    <td style="padding: 15px; font-size: 14px; color: #666;">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    <td style="padding: 15px;">
                        <div style="display: flex; gap: 8px;">
                            <button onclick="openResetPasswordModal({{ $user->id }}, '{{ $user->name }}')" class="btn btn-sm" style="background: #ff9800;">
                                Redefinir Senha
                            </button>

                            @if(!$user->is_admin && $user->id !== Auth::id())
                                <form method="POST" action="{{ route('admin.users.promote', $user->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm" style="background: #28a745;" onclick="return confirm('Promover {{ $user->name }} a administrador?')">
                                        Promover a Admin
                                    </button>
                                </form>
                            @elseif($user->is_admin && $user->id !== Auth::id())
                                <form method="POST" action="{{ route('admin.users.demote', $user->id) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm" style="background: #dc3545;" onclick="return confirm('Remover privilégios de admin de {{ $user->name }}?')">
                                        Remover Admin
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #e0e0e0; display: flex; justify-content: space-between; align-items: center;">
        <div style="color: #666; font-size: 14px;">
            Mostrando {{ $users->firstItem() }} a {{ $users->lastItem() }} de {{ $users->total() }} usuários
        </div>
        <div style="display: flex; gap: 10px;">
            @if($users->onFirstPage())
                <span class="btn btn-sm btn-secondary" style="opacity: 0.5; cursor: not-allowed;">Anterior</span>
            @else
                <a href="{{ $users->previousPageUrl() }}" class="btn btn-sm btn-secondary">Anterior</a>
            @endif

            @if($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="btn btn-sm">Próxima</a>
            @else
                <span class="btn btn-sm" style="opacity: 0.5; cursor: not-allowed;">Próxima</span>
            @endif
        </div>
    </div>
    @endif
</div>

<!-- Reset Password Modal -->
<div id="resetPasswordModal" class="modal">
    <div class="modal-content-custom">
        <h2 style="font-size: 22px; font-weight: 700; margin-bottom: 10px; color: #333;">Redefinir Senha</h2>
        <p style="color: #666; margin-bottom: 25px;">Definir nova senha para <span id="userName" style="color: #3498db; font-weight: 600;"></span></p>

        <form id="resetPasswordForm" method="POST" action="">
            @csrf
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333; font-size: 14px;">Nova Senha</label>
                <input
                    type="password"
                    name="new_password"
                    required
                    style="width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"
                    placeholder="Mínimo 8 caracteres"
                >
            </div>

            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #333; font-size: 14px;">Confirmar Nova Senha</label>
                <input
                    type="password"
                    name="new_password_confirmation"
                    required
                    style="width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px;"
                    placeholder="Digite a senha novamente"
                >
            </div>

            <div style="display: flex; gap: 10px;">
                <button
                    type="button"
                    onclick="closeResetPasswordModal()"
                    class="btn btn-secondary"
                    style="flex: 1;"
                >
                    Cancelar
                </button>
                <button
                    type="submit"
                    class="btn"
                    style="flex: 1; background: #ff9800;"
                >
                    Redefinir
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openResetPasswordModal(userId, userName) {
        document.getElementById('userName').textContent = userName;
        document.getElementById('resetPasswordForm').action = `/admin/users/${userId}/reset-password`;
        document.getElementById('resetPasswordModal').classList.add('active');
    }

    function closeResetPasswordModal() {
        document.getElementById('resetPasswordModal').classList.remove('active');
        document.getElementById('resetPasswordForm').reset();
    }

    // Close modal when clicking outside
    document.getElementById('resetPasswordModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeResetPasswordModal();
        }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeResetPasswordModal();
        }
    });
</script>
@endpush
