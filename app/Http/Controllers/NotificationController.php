<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Listar todas as notificações.
     */
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'message' => 'Notificações carregadas com sucesso',
            'data' => $notifications,
        ]);
    }

    /**
     * Configurar notificações personalizadas.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string|max:100', // Exemplo: "email", "sms"
            'threshold' => 'required|numeric', // Exemplo: valor para disparo do alerta
        ]);

        $notification = Notification::updateOrCreate(
            ['user_id' => $validated['user_id'], 'type' => $validated['type']],
            ['threshold' => $validated['threshold']]
        );

        return response()->json([
            'message' => 'Configuração de notificação atualizada com sucesso',
            'data' => $notification,
        ]);
    }
}
