<?php

namespace App\Http\Controllers;

use App\Models\Assinatura;
use Illuminate\Http\Request;

class AssinaturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Verifica se o usuário é admin
        if ($request->user()->role !== 'admin') {
            return response()->json(['error' => 'Acesso negado.'], 403);
        }

        // Busca todas as assinaturas
        $assinaturas = Assinatura::all()->map(function ($assinatura) {
            return [
                'id' => $assinatura->id,
                'nome' => $assinatura->nome,
                'valor_mensal' => number_format($assinatura->valor_mensal / 100, 2, ',', '.'), // Centavos para reais
                'valor_total' => number_format($assinatura->valor_total / 100, 2, ',', '.'),   // Centavos para reais
                'dias' => $assinatura->dias,
                'descricao' => $assinatura->descricao,
                'status' => $assinatura->status,
                'created_at' => $assinatura->created_at->format('d/m/Y H:i:s'),
                'updated_at' => $assinatura->updated_at->format('d/m/Y H:i:s'),
            ];
        });

        // Retorna as assinaturas em formato JSON
        return response()->json($assinaturas);
    }
}
