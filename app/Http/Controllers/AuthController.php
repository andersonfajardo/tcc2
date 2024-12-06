<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Realizar login do usuário.
     */
    public function login(Request $request)
    {

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();

            return response()->json([
                'message' => 'Login realizado com sucesso',
                'user' => Auth::user(),
            ]);
        }

        return response()->json([
            'message' => 'Credenciais inválidas',
        ], 401);
    }

    /**
     * Recuperar senha (envio de e-mail com link).
     */
    public function recoverPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink(['email' => $validated['email']]);

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Link de recuperação enviado para o e-mail',
            ]);
        }

        return response()->json([
            'message' => 'Falha ao enviar o link de recuperação',
        ], 500);
    }

    /**
     * Atualizar senha do usuário.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($validated['current_password'], $user->passwordhash)) {
            return response()->json([
                'message' => 'Senha atual incorreta',
            ], 400);
        }

        $user->update([
            'passwordhash' => Hash::make($validated['new_password']),
        ]);

        return response()->json([
            'message' => 'Senha atualizada com sucesso',
        ]);
    }

    /**
     * Fazer logout do usuário.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Logout realizado com sucesso',
        ]);
    }
}

