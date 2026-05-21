<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    /**
     * Change the authenticated user's password
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'A senha atual está incorreta.']);
        }

        // Update password
        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return back()->with('success', 'Senha alterada com sucesso!');
    }

    /**
     * Display all users (admin only)
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users', compact('users'));
    }

    /**
     * Reset a user's password (admin only)
     */
    public function resetPassword(Request $request, $id)
    {
        $validated = $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::findOrFail($id);

        // Prevent admin from resetting their own password through this method
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Use o formulário "Alterar Senha" para mudar sua própria senha.');
        }

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return back()->with('success', "Senha do usuário {$user->name} foi redefinida com sucesso!");
    }

    /**
     * Promote a user to admin (admin only)
     */
    public function promoteToAdmin($id)
    {
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            return back()->with('error', 'Este usuário já é um administrador.');
        }

        $user->role = 'admin';
        $user->save();

        return back()->with('success', "{$user->name} foi promovido a administrador!");
    }

    /**
     * Demote an admin to regular user (admin only)
     */
    public function demoteFromAdmin($id)
    {
        $user = User::findOrFail($id);

        // Prevent demoting yourself
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Você não pode remover seus próprios privilégios de administrador.');
        }

        if (!$user->isAdmin()) {
            return back()->with('error', 'Este usuário já é um usuário regular.');
        }

        $user->role = 'user';
        $user->save();

        return back()->with('success', "{$user->name} foi rebaixado para usuário regular.");
    }
}
