<?php

namespace App\Http\Controllers;

use App\Models\CaixaMovimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaixaMovimentoController extends Controller
{

    // Verificar o status do caixa
    public function verificarStatusCaixa()
    {
        $caixaAtual = CaixaMovimento::where('empresa_id', Auth::user()->empresa_id)
            ->where('status', 'aberto')
            ->first();

        return response()->json([
            'status' => $caixaAtual ? 'aberto' : 'fechado',
            'caixa' => $caixaAtual,
        ]);
    }

    // Abrir o caixa
    public function abrirCaixa(Request $request)
    {
        $request->validate([
            'valor_inicial' => 'required|numeric|min:0',
        ]);

        try {
            $caixaAberto = CaixaMovimento::where('empresa_id', Auth::user()->empresa_id)
                ->where('status', 'aberto')
                ->first();

            if ($caixaAberto) {
                return response()->json(['error' => 'Já existe um caixa aberto.'], 400);
            }

            $caixa = CaixaMovimento::create([
                'empresa_id' => Auth::user()->empresa_id,
                'data_hora_abertura' => now(),
                'valor_inicial' => $request->valor_inicial,
                'status' => 'aberto',
            ]);

            return response()->json(['success' => true, 'caixa' => $caixa], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao abrir o caixa: ' . $e->getMessage()], 500);
        }
    }

    // Fechar o caixa
    public function fecharCaixa(Request $request)
    {
        $request->validate([
            'valor_final' => 'required|numeric|min:0',
            'diferenca' => 'required|numeric|min:0',
        ]);

        try {
            // Recupera o caixa atual
            $caixaAtual = CaixaMovimento::where('empresa_id', Auth::user()->empresa_id)
                ->where('status', 'aberto')
                ->first();

            if (!$caixaAtual) {
                return response()->json(['error' => 'Não há caixa aberto.'], 400);
            }

            // Atualiza os dados do caixa, incluindo o valor final e a diferença
            $caixaAtual->update([
                'data_hora_fechamento' => now(),
                'valor_final' => $request->valor_final,
                'status' => 'fechado',
                'diferenca' => $request->diferenca,  // Atualizando a diferença
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao fechar o caixa: ' . $e->getMessage()], 500);
        }
    }



}
