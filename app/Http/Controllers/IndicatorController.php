<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KPI;
use App\Models\OKR;
use App\Models\Data;

class IndicatorController extends Controller
{
    /**
     * Listar todos os indicadores (OKRs e KPIs).
     */
    public function index()
    {
        $indicators = KPI::with('okr', 'user')->get();

        return response()->json([
            'message' => 'Indicadores carregados com sucesso',
            'data' => $indicators,
        ]);
    }

    /**
     * Criar um novo indicador.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'okr_id' => 'nullable|exists:okrs,id',
            'kpititle' => 'required|string|max:100',
            'kpidescription' => 'nullable|string|max:255',
            'kpistargetvalue' => 'required|numeric',
            'startdate' => 'required|date',
            'enddate' => 'required|date|after_or_equal:startdate',
            'indicator_type' => 'required|exists:indicatortype,id',
        ]);

        $indicator = KPI::create($validated);

        return response()->json([
            'message' => 'Indicador criado com sucesso',
            'data' => $indicator,
        ], 201);
    }

    /**
     * Atualizar um indicador existente.
     */
    public function update(Request $request, $id)
    {
        $indicator = KPI::findOrFail($id);

        $validated = $request->validate([
            'kpititle' => 'sometimes|string|max:100',
            'kpidescription' => 'nullable|string|max:255',
            'kpistargetvalue' => 'sometimes|numeric',
            'startdate' => 'sometimes|date',
            'enddate' => 'sometimes|date|after_or_equal:startdate',
            'indicator_type' => 'sometimes|exists:indicatortype,id',
        ]);

        $indicator->update($validated);

        return response()->json([
            'message' => 'Indicador atualizado com sucesso',
            'data' => $indicator,
        ]);
    }

    /**
     * Excluir um indicador.
     */
    public function destroy($id)
    {
        $indicator = KPI::findOrFail($id);
        $indicator->delete();

        return response()->json([
            'message' => 'Indicador excluído com sucesso',
        ]);
    }

    /**
     * Validar dados para um indicador.
     */
    public function validateData(Request $request)
    {
        $validated = $request->validate([
            'id_indicator' => 'required|exists:kpi,id',
            'value' => 'required|numeric',
        ]);

        // Simulação de validação lógica (exemplo básico)
        $indicator = KPI::find($validated['id_indicator']);
        $isValid = $validated['value'] <= $indicator->kpistargetvalue;

        return response()->json([
            'message' => $isValid ? 'Dados válidos' : 'Dados inválidos',
            'valid' => $isValid,
        ]);
    }

    /**
     * Acessar o histórico de dados de um indicador.
     */
    public function getHistory($id)
    {
        $indicator = KPI::findOrFail($id);
        $history = Data::where('id_indicator', $id)->get();

        return response()->json([
            'message' => 'Histórico de dados carregado com sucesso',
            'indicator' => $indicator,
            'data' => $history,
        ]);
    }
}

