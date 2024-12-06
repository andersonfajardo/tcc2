<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\APIToken;
use Illuminate\Support\Str;

class IntegrationController extends Controller
{
    /**
     * Obter detalhes da API aberta.
     */
    public function getOpenApi()
    {
        return response()->json([
            'message' => 'Bem-vindo à API aberta do sistema OKR/KPI.',
            'endpoints' => [
                'GET /api/indicators' => 'Listar indicadores públicos',
                'GET /api/templates' => 'Listar templates de indicadores públicos',
            ],
        ]);
    }

    /**
     * Criar ou gerenciar integrações externas.
     */
    public function createIntegration(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tool_name' => 'required|string|max:100', // Nome da ferramenta, ex.: Zapier, Power BI
        ]);

        // Gerar um token único para integração
        $token = Str::random(60);

        $apiToken = APIToken::create([
            'user_id' => $validated['user_id'],
            'token' => $token,
            'expirationdate' => now()->addDays(30), // Token expira em 30 dias
        ]);

        return response()->json([
            'message' => 'Integração criada com sucesso',
            'token' => $token,
            'expires_at' => $apiToken->expirationdate,
        ], 201);
    }
}
