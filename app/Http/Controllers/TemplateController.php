<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Template;

class TemplateController extends Controller
{
    /**
     * Listar todos os templates.
     */
    public function index()
    {
        $templates = Template::all();
        return response()->json([
            'message' => 'Templates carregados com sucesso',
            'data' => $templates,
        ]);
    }

    /**
     * Criar um novo template.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'type' => 'required|string|max:50', // Exemplo: "OKR", "KPI"
        ]);

        $template = Template::create($validated);

        return response()->json([
            'message' => 'Template criado com sucesso',
            'data' => $template,
        ], 201);
    }

    /**
     * Atualizar um template existente.
     */
    public function update(Request $request, $id)
    {
        $template = Template::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'description' => 'nullable|string|max:255',
            'type' => 'sometimes|string|max:50',
        ]);

        $template->update($validated);

        return response()->json([
            'message' => 'Template atualizado com sucesso',
            'data' => $template,
        ]);
    }

    /**
     * Excluir um template.
     */
    public function destroy($id)
    {
        $template = Template::findOrFail($id);
        $template->delete();

        return response()->json([
            'message' => 'Template excluído com sucesso',
        ]);
    }
}
