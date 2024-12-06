<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActionPlan;
use App\Models\KPI;

class ActionPlanController extends Controller
{
    /**
     * Listar todos os planos de ação.
     */
    public function index()
    {
        $actionPlans = ActionPlan::with('kpi')->get(); // Inclui dados relacionados ao KPI
        return response()->json($actionPlans);
    }

    /**
     * Criar um novo plano de ação.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'what' => 'required|string|max:255',
            'why' => 'required|string|max:255',
            'who' => 'required|string|max:255',
            'when' => 'required|date',
            'where' => 'nullable|string|max:255',
            'how' => 'nullable|string|max:255',
            'howmuch' => 'nullable|numeric',
            'id_kpi' => 'required|exists:kpi,id',
        ]);

        $actionPlan = ActionPlan::create($validated);

        return response()->json([
            'message' => 'Plano de ação criado com sucesso',
            'data' => $actionPlan,
        ], 201);
    }

    /**
     * Atualizar um plano de ação existente.
     */
    public function update(Request $request, $id)
    {
        $actionPlan = ActionPlan::findOrFail($id);

        $validated = $request->validate([
            'what' => 'sometimes|string|max:255',
            'why' => 'sometimes|string|max:255',
            'who' => 'sometimes|string|max:255',
            'when' => 'sometimes|date',
            'where' => 'nullable|string|max:255',
            'how' => 'nullable|string|max:255',
            'howmuch' => 'nullable|numeric',
            'id_kpi' => 'nullable|exists:kpi,id',
        ]);

        $actionPlan->update($validated);

        return response()->json([
            'message' => 'Plano de ação atualizado com sucesso',
            'data' => $actionPlan,
        ]);
    }

    /**
     * Excluir um plano de ação.
     */
    public function destroy($id)
    {
        $actionPlan = ActionPlan::findOrFail($id);
        $actionPlan->delete();

        return response()->json([
            'message' => 'Plano de ação excluído com sucesso',
        ]);
    }
}
