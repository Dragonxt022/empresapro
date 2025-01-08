<?php

namespace App\Http\Controllers;

use App\Models\Plano;
use Illuminate\Http\Request;

class PlanoController extends Controller
{
    /**
     * Exibe a lista de planos.
     */
    public function index()
    {
        $planos = Plano::where('nome', '!=', 'Free')->get(); // Exclui o plano "Free"

        // Formatar os valores para o formato de reais
        $planos->transform(function ($plano) {
            $plano->valor_total = number_format($plano->valor_total, 2, ',', '.'); // Formata em reais
            $plano->valor_mensal = number_format($plano->valor_mensal, 2, ',', '.'); // Formata mensal também

            return $plano;
        });

        return response()->json($planos);
    }


    /**
     * Cria um novo plano.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'id_produto_stripe' => 'required|string|max:255',
            'valor_mensal' => 'required|numeric',
            'valor_total' => 'required|numeric',
            'duracao_dias' => 'required|integer',
        ]);

        $plano = Plano::create($request->all());
        return response()->json($plano, 201);
    }

    /**
     * Exibe um plano específico.
     */
    public function show($id)
    {
        $plano = Plano::findOrFail($id);
        return response()->json($plano);
    }

    /**
     * Atualiza um plano.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'id_produto_stripe' => 'required|string|max:255',
            'valor_mensal' => 'required|numeric',
            'valor_total' => 'required|numeric',
            'duracao_dias' => 'required|integer',
        ]);

        $plano = Plano::findOrFail($id);
        $plano->update($request->all());

        return response()->json($plano);
    }

    /**
     * Exclui um plano.
     */
    public function destroy($id)
    {
        $plano = Plano::findOrFail($id);
        $plano->delete();

        return response()->json(null, 204);
    }
}
