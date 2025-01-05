<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminControllerEmpresas extends Controller
{
    // Método para retornar a lista de empresas
    public function listarEmpresas()
    {
        // Verificar se o usuário é admin
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Acesso não autorizado. Somente administradores podem acessar.'
            ], 403);
        }

        // Recuperar todas as empresas
        $empresas = Empresa::all();

        // Retornar a resposta com as empresas em formato JSON
        return response()->json([
            'success' => true,
            'data' => $empresas,
        ]);
    }

    // Método para retornar os detalhes de uma empresa
    public function detalhes($empresaId)
    {
        // Verificar se o usuário é admin
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Acesso não autorizado. Somente administradores podem acessar.'
            ], 403);
        }

        // Buscar a empresa pelo ID no banco de dados
        $empresa = Empresa::find($empresaId);

        // Verificar se a empresa foi encontrada
        if (!$empresa) {
            return response()->json([
                'success' => false,
                'message' => 'Empresa não encontrada'
            ], 404);
        }

        // Retornar os dados da empresa
        return response()->json([
            'success' => true,
            'empresa' => $empresa
        ], 200);
    }

    // Método para deletar uma empresa
    public function deletar($empresaId)
    {
        // Verificar se o usuário é admin
        if (Auth::user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Acesso não autorizado. Somente administradores podem acessar.'
            ], 403);
        }

        // Buscar a empresa pelo ID no banco de dados
        $empresa = Empresa::find($empresaId);

        // Verificar se a empresa foi encontrada
        if (!$empresa) {
            return response()->json([
                'success' => false,
                'message' => 'Empresa não encontrada'
            ], 404);
        }

        try {
            // Deletar a empresa
            $empresa->delete();

            return response()->json([
                'success' => true,
                'message' => 'Empresa excluída com sucesso'
            ], 200);
        } catch (\Exception $e) {
            // Em caso de erro, retorna a mensagem de erro
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir a empresa: ' . $e->getMessage()
            ], 500);
        }
    }
}
