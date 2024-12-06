<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DashboardConfig;
use App\Models\KPI;
use App\Models\OKR;

class DashboardController extends Controller
{
    /**
     * Retornar o layout e dados do dashboard.
     */
    public function index(Request $request)
    {
        $userId = auth()->id();

        // Configurações do layout do dashboard
        $dashboardConfig = DashboardConfig::where('user_id', $userId)->get();

        // Dados do dashboard (OKRs e KPIs)
        $okrs = OKR::where('user_id', $userId)->get();
        $kpis = KPI::where('user_id', $userId)->get();

        return response()->json([
            'message' => 'Dashboard carregado com sucesso',
            'layout' => $dashboardConfig,
            'data' => [
                'okrs' => $okrs,
                'kpis' => $kpis,
            ],
        ]);
    }

    /**
     * Atualizar o layout do dashboard.
     */
    public function updateLayout(Request $request)
    {
        $userId = auth()->id();

        $validated = $request->validate([
            'widgets' => 'required|array',
            'widgets.*.id' => 'required|exists:dashboardconfig,id',
            'widgets.*.positionx' => 'required|integer',
            'widgets.*.positiony' => 'required|integer',
        ]);

        foreach ($validated['widgets'] as $widget) {
            DashboardConfig::where('id', $widget['id'])
                ->where('user_id', $userId)
                ->update([
                    'positionx' => $widget['positionx'],
                    'positiony' => $widget['positiony'],
                ]);
        }

        return response()->json([
            'message' => 'Layout do dashboard atualizado com sucesso',
        ]);
    }
}


