<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Indicator;
use App\Models\OKR;
use App\Models\Data;

class IndicatorController extends Controller
{
    /**
     * Listar todos os indicadores (OKRs e KPIs).
     */
    public function index()
    {
        $userId = auth()->id(); // Obtém o ID do usuário logado
        //$indicators = Indicator::where('enable', 1)->get();
        $indicators = Indicator::where('user_id', $userId)
        ->where('enable', 1) // Apenas indicadores ativos
        ->get();
        return response()->json($indicators);
    }

    /**
     * Criar um novo indicador.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kpititle' => 'required|string|max:100',
            'kpidescription' => 'nullable|string|max:255',
            'indicator_type' => 'required|in:1,2,3', // Gráficos válidos
            'okr' => 'required|boolean', // 0 ou 1
        ]);
        
        // Adicionar campos fixos
        $validated['user_id'] = auth()->id();
        $validated['startdate'] = now();
        $validated['createdate'] = now();
        $validated['enable'] = 1; // Sempre habilitado
        $validated['dashboard'] = 0; // Sempre exibido = false
        
        // Inserir no banco
        $indicator = Indicator::create($validated);

        return response()->json($indicator, 201);
    }

    /**
     * Atualizar um indicador existente.
     */
    public function update(Request $request, $id)
    {
        $indicator = Indicator::findOrFail($id);

        $validated = $request->validate([
            'kpititle' => 'required|string|max:100',
            'kpidescription' => 'nullable|string|max:255',
            'kpistargetvalue' => 'required|numeric',
            'startdate' => 'required|date',
            'enddate' => 'required|date',
            'indicator_type' => 'required|integer',
            'okr' => 'required|integer',
            'enable' => 'required|integer',
            'dashboard' => 'required|integer',
        ]);

        $indicator->update($validated);

        return response()->json($indicator);
    }

    /**
     * Excluir um indicador.
     */
    public function destroy($id)
    {
        $indicator = Indicator::findOrFail($id);
        $indicator->update(['enable' => 0]);

        return response()->json(['message' => 'Indicator disabled successfully']);
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

    public function patch(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|int',
            'dashboard' => 'required|boolean',
        ]);
        // Inserir no banco
        $indicator = Indicator::find($validated['id']);
        $indicator->update(['dashboard' => $validated['dashboard']]);
        return response()->json($indicator, 201);
    }

    public function patchEnable(Request $request)
{
    $validated = $request->validate([
        'id' => 'required|int',
        'enable' => 'required|boolean',
    ]);
    
    $indicator = Indicator::find($validated['id']);
    $indicator->update(['enable' => $validated['enable']]);

    return response()->json($indicator, 200); // Alterei o código de status para 200 (OK) para respeitar o padrão
}
}

