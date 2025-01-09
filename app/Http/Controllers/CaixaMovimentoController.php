<?php

namespace App\Http\Controllers;

use App\Models\CaixaMovimento;
use App\Models\CaixaOperacao;
use App\Models\PaymentMethod;
use App\Models\Venda;
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
            'diferenca' => 'required|numeric',
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

    public function gerarRelatorio()
    {
        // Recupera o ID da empresa do usuário autenticado
        $empresaId = Auth::user()->empresa_id;

        // Busca os movimentos de caixa da empresa e ordena pela data de abertura em ordem decrescente
        $movimentos = CaixaMovimento::where('empresa_id', $empresaId)
            ->orderBy('data_hora_abertura', 'desc') // Ordena pela data de abertura, decrescente
            ->get();

        // Criar uma estrutura de dados com os detalhes dos movimentos
        $relatorio = $movimentos->map(function ($movimento) {
            // Calcular a diferença entre valor inicial e valor final em reais
            $valor_inicial = $movimento->valor_inicial / 100;
            $valor_final = $movimento->valor_final / 100;
            $diferenca = $movimento->diferenca / 100;

            // Retorna o relatório com a diferença calculada
            return [
                'id' => $movimento->id,
                'data_hora_abertura' => $movimento->data_hora_abertura,
                'data_hora_fechamento' => $movimento->data_hora_fechamento,
                'valor_inicial' => number_format($valor_inicial, 2, ',', '.'), // Converte e formata o valor inicial
                'valor_final' => number_format($valor_final, 2, ',', '.'), // Converte e formata o valor final
                'diferenca' => number_format($diferenca, 2, ',', '.'), // Converte e formata a diferença
                'status' => $movimento->status,
            ];
        });

        // Retorna o relatório completo em formato JSON
        return response()->json($relatorio);
    }


    public function gerarDetalhesCaixa($id)
    {
        // Recupera o caixa específico pelo ID e empresa do usuário
        $caixaAberto = CaixaMovimento::where('id', $id)
            ->where('empresa_id', Auth::user()->empresa_id)
            ->first();

        if (!$caixaAberto) {
            return response()->json([
                'success' => false,
                'message' => 'Caixa não encontrado.',
            ], 404);
        }

        // Busca todos os métodos de pagamento ativos da empresa
        $metodosPagamento = PaymentMethod::where('empresa_id', Auth::user()->empresa_id)
            ->where('is_active', 1)
            ->get();

        // Inicializa os totais por método de pagamento
        $totaisPorMetodo = [];
        foreach ($metodosPagamento as $metodo) {
            $totaisPorMetodo[$metodo->type] = [
                'nome' => $metodo->name,
                'total' => 0,
            ];
        }

        // Busca todas as vendas associadas ao caixa específico
        $vendas = Venda::with(['pagamentos', 'produtos'])
            ->where('caixa_movimento_id', $caixaAberto->id)
            ->where('empresa_id', Auth::user()->empresa_id)
            ->where('status', '!=', 'pendente') // Ignorar vendas pendentes
            ->get();

        // Calcula os totais por método de pagamento
        foreach ($vendas as $venda) {
            foreach ($venda->pagamentos as $pagamento) {
                $metodo = PaymentMethod::find($pagamento->metodo);
                if ($metodo && isset($totaisPorMetodo[$metodo->type])) {
                    $totaisPorMetodo[$metodo->type]['total'] += $pagamento->valor;
                }
            }
        }

        // Soma entradas e saídas
        $totalEntradas = CaixaOperacao::where('caixa_movimento_id', $caixaAberto->id)
            ->where('tipo', 'entrada')
            ->sum('valor');

        $totalSaidas = CaixaOperacao::where('caixa_movimento_id', $caixaAberto->id)
            ->where('tipo', 'saida')
            ->sum('valor');

        // Recupera as operações realizadas no caixa
        $operacoes = CaixaOperacao::where('caixa_movimento_id', $caixaAberto->id)
            ->orderBy('data_hora', 'asc')
            ->get();

        // Detalha as operações (entrada ou saída)
        $detalhesOperacoes = $operacoes->map(function ($operacao) {
            return [
                'descricao' => $operacao->descricao,
                'tipo' => $operacao->tipo, // 'entrada' ou 'saida'
                'valor' => number_format($operacao->valor / 100, 2, ',', '.'), // Formata o valor
                'data_hora' => $operacao->data_hora, // Formata a data
            ];
        });

        $valorAbertura = $caixaAberto->valor_inicial;
        $valorAFinal = $caixaAberto->valor_final;
        $valorDiferenca = $caixaAberto->diferenca;

        // Formatar valores para exibição
        foreach ($totaisPorMetodo as &$metodo) {
            $metodo['total'] = number_format($metodo['total'] / 100, 2, ',', '.');
        }

        // Detalhes das vendas
        $detalhesVendas = $vendas->map(function ($venda) {
            return [
                'id' => $venda->id,
                'mesa' => $venda->mesa ? $venda->mesa->nome : 'Sem mesa',
                'cliente' => $venda->cliente ? $venda->cliente : 'Não Informado',
                'valor_total' => number_format($venda->valor_total / 100, 2, ',', '.'),
                'status' => $venda->status,
                'produtos' => $venda->produtos->map(function ($produto) {
                    return [
                        'nome' => $produto->nome,
                        'quantidade' => $produto->quantidade,
                        'valor_unitario' => number_format($produto->valor_unitario / 100, 2, ',', '.'),
                        'valor_total' => number_format($produto->valor_total / 100, 2, ',', '.'),
                    ];
                }),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'status_caixa' => $caixaAberto->status,
                'data_hora_abertura' => $caixaAberto->data_hora_abertura
                    ? $caixaAberto->data_hora_abertura
                    : 'N/A',
                'data_hora_fechamento' => $caixaAberto->data_hora_fechamento
                    ? $caixaAberto->data_hora_fechamento
                    : 'Ainda não fechado',
                'valor_abertura' => number_format($valorAbertura / 100, 2, ',', '.'),
                'total_entradas' => number_format($totalEntradas / 100, 2, ',', '.'),
                'total_saidas' => number_format($totalSaidas / 100, 2, ',', '.'),
                'valor_Diferenca' => number_format($valorDiferenca / 100, 2, ',', '.'),
                'valor_total_final' => number_format($valorAFinal / 100, 2, ',', '.'),
                'totais_por_metodo' => $totaisPorMetodo,
                'vendas' => $detalhesVendas,
                'operacoes' => $detalhesOperacoes, // Incluindo as operações no retorno
            ],
        ], 200);
    }
}
