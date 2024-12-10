<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Listar todos os usuários.
     */
    public function index()
    {
        $users = User::with('company')->get(); // Inclui informações da empresa
        return response()->json([
            'message' => 'Usuários carregados com sucesso',
            'data' => $users,
        ]);
    }

    /**
     * Criar um novo usuário.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'company_id' => 'required|exists:company,id',
            //'id' => 'nullable|exists:company,id',
            'name' => 'required|string|max:100',
            'username' => 'required|string|max:30|unique:User',
            'email' => 'required|email|max:30|unique:User',
            'password' => 'required|string|min:6',
            'datacreated' => 'nullable|date',
            'active' => 'required|boolean',
        ]);

        $validated['passwordhash'] = Hash::make($validated['passwordhash']); // Criptografa a senha

        $user = User::create($validated);

        return response()->json([
            'message' => 'Usuário criado com sucesso',
            'data' => $user,
        ], 201);
    }

    /**
     * Atualizar um usuário existente.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'id' => 'nullable|exists:company,id',
            'name' => 'nullable|string|max:100',
            'username' => 'nullable|string|max:30|unique:user,username,' . $id,
            'email' => 'nullable|email|max:30|unique:user,email,' . $id,
            'passwordhash' => 'nullable|string|min:6',
            'active' => 'nullable|boolean',
        ]);

        if (isset($validated['passwordhash'])) {
            $validated['passwordhash'] = Hash::make($validated['passwordhash']); // Atualiza senha criptografada
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Usuário atualizado com sucesso',
            'data' => $user,
        ]);
    }

    /**
     * Excluir um usuário.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'message' => 'Usuário excluído com sucesso',
        ]);
    }

    public function getUserActionPlan()
{
    $user = Auth::user();

    // Consulta os planos de ação relacionados ao usuário logado
    $actionPlans = $user->actionPlansPerId()->get(); // O relacionamento corrigido será usado aqui

    // Retorna o resultado como JSON
    return response()->json($actionPlans);
}
}

