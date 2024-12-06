<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    /**
     * Listar todas as empresas.
     */
    public function index()
    {
        $companies = Company::with('users')->get(); // Inclui os usuários associados
        return response()->json([
            'message' => 'Empresas carregadas com sucesso',
            'data' => $companies,
        ]);
    }

    /**
     * Criar uma nova empresa.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'adress' => 'required|string|max:100',
            'contactmail' => 'required|email|max:30',
            'contactphone' => 'nullable|string|max:100',
            'createdate' => 'nullable|date',
        ]);

        $company = Company::create($validated);

        return response()->json([
            'message' => 'Empresa criada com sucesso',
            'data' => $company,
        ], 201);
    }

    /**
     * Atualizar uma empresa existente.
     */
    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);

        $validated = $request->validate([
            'name' => 'nullable|string|max:100',
            'adress' => 'nullable|string|max:100',
            'contactmail' => 'nullable|email|max:30',
            'contactphone' => 'nullable|string|max:100',
        ]);

        $company->update($validated);

        return response()->json([
            'message' => 'Empresa atualizada com sucesso',
            'data' => $company,
        ]);
    }

    /**
     * Excluir uma empresa.
     */
    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return response()->json([
            'message' => 'Empresa excluída com sucesso',
        ]);
    }
}
