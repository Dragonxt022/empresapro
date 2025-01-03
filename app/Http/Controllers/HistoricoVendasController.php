<?php

namespace App\Http\Controllers;

use App\Models\CaixaMovimento;
use App\Models\CaixaOperacao;
use App\Models\PaymentMethod;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoricoVendasController extends Controller
{

    /**
     * Display the specified resource.
     */
    // Método para buscar detalhes de uma venda com base no ID
    public function show($id)
    {
        try {
            // Identificando o usuário autenticado e sua empresa
            $user = Auth::user();
            $empresaId = $user->empresa_id;

            // Buscando a venda pelo ID da venda, associada à empresa do usuário
            $venda = \App\Models\Venda::with(['produtos', 'pagamentos'])
                ->where('id', $id) // ID da venda
                ->where('empresa_id', $empresaId) // Empresa do usuário
                ->first();

            // Verificando se a venda existe
            if (!$venda) {
                return response()->json([
                    'success' => false,
                    'message' => 'Venda não encontrada ou já finalizada.',
                ], 404);
            }

            // Mapeando os dados da venda
                $vendaFormatada = [
                    'id' => $venda->id,
                    'cliente' => $venda->cliente ?? 'Não informado',
                    'data_venda' => $venda->created_at->format('d-m-Y / H:i:s'),
                    'valor_total' => number_format($venda->valor_total / 100, 2, ',', '.'),
                    'acrescimo' => number_format($venda->acrescimo / 100, 2, ',', '.'),
                    'desconto' => number_format($venda->desconto / 100, 2, ',', '.'),

                    // Incluindo informações do vendedor
                    'vendedor' => $venda->vendedor ? [
                        'first_name' => $venda->vendedor->first_name,
                        'last_name' => $venda->vendedor->last_name,
                        'full_name' => $venda->vendedor->first_name . ' ' . $venda->vendedor->last_name,
                    ] : [
                        'first_name' => 'Não informado',
                        'last_name' => '',
                        'full_name' => 'Não informado',
                    ],

                    // Incluindo produtos
                    'produtos' => $venda->produtos->map(function ($produto) {
                        return [
                            'nome' => $produto->nome,
                            'quantidade' => $produto->quantidade,
                            'valor_unitario' => number_format($produto->valor_unitario / 100, 2, ',', '.'),
                            'valor_total' => number_format($produto->valor_total / 100, 2, ',', '.'),
                        ];
                    }),

                    // Incluindo pagamentos
                    'pagamentos' => $venda->pagamentos->map(function ($pagamento) {
                        $metodoPagamento = \App\Models\PaymentMethod::find($pagamento->metodo);
                        return [
                            'name' => $metodoPagamento ? $metodoPagamento->name : 'Desconhecido',
                            'valor' => number_format($pagamento->valor / 100, 2, ',', '.'),
                        ];
                    }),
                ];


            // Retornando os dados da venda formatados
            return response()->json([
                'success' => true,
                'venda' => $vendaFormatada,
            ], 200);

        } catch (\Exception $e) {
            // Capturando erros e retornando uma resposta adequada
            return response()->json([
                'success' => false,
                'message' => 'Erro ao recuperar a venda.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function obterDadosCaixa()
    {
        // Busca o caixa aberto para a empresa atual do usuário
        $caixaAberto = CaixaMovimento::where('empresa_id', Auth::user()->empresa_id)
            ->where('status', 'aberto')
            ->first();

        if (!$caixaAberto) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhum caixa aberto encontrado.',
            ], 404);
        }

        // Pegando o ID do método de pagamento "Dinheiro"
        $metodoDinheiro = PaymentMethod::where('type', 'D')
            ->where('empresa_id', Auth::user()->empresa_id) // Certificando-se de que a empresa do usuário é a correta
            ->where('is_active', 1) // Somente métodos ativos
            ->first(); // Retorna o primeiro método que corresponder

        if (!$metodoDinheiro) {
            // Caso não exista o método de pagamento "Dinheiro"
            return response()->json([
                'success' => false,
                'message' => 'Método de pagamento Dinheiro não encontrado.',
            ], 404);
        }

        // Inicializando variável para o total de vendas em dinheiro
        $totalVendasDinheiro = 0;

        // Buscando todas as vendas do caixa aberto para a empresa
        $vendas = Venda::where('caixa_movimento_id', $caixaAberto->id)
            ->where('empresa_id', Auth::user()->empresa_id) // Certificando-se de que a empresa do usuário é a correta
            ->get();

        // Percorrendo todas as vendas para somar os pagamentos em dinheiro
        foreach ($vendas as $venda) {
            // Somando o valor dos pagamentos feitos com o método "Dinheiro"
            $pagamentosDinheiro = $venda->pagamentos->where('metodo', $metodoDinheiro->id);

            foreach ($pagamentosDinheiro as $pagamento) {
                $totalVendasDinheiro += $pagamento->valor;
            }
        }

        // Somando o valor total das vendas associadas ao caixa aberto
        $totalVendas = $vendas->sum('valor_total');

        // Obtendo o valor de abertura do caixa
        $valorAbertura = $caixaAberto->valor_inicial;

        // Somando os valores das operações de entrada e saída
        $totalEntradas = CaixaOperacao::where('caixa_movimento_id', $caixaAberto->id)
            ->where('tipo', 'entrada')
            ->sum('valor');

        $totalSaidas = CaixaOperacao::where('caixa_movimento_id', $caixaAberto->id)
            ->where('tipo', 'saida')
            ->sum('valor');

        // Convertendo de centavos para reais
        $totalVendasEmReais = number_format($totalVendas / 100, 2, ',', '.');
        $totalVendasDinheiroEmReais = number_format($totalVendasDinheiro / 100, 2, ',', '.');
        $valorAberturaEmReais = number_format($valorAbertura / 100, 2, ',', '.');
        $totalEntradasEmReais = number_format($totalEntradas / 100, 2, ',', '.');
        $totalSaidasEmReais = number_format($totalSaidas / 100, 2, ',', '.');

        // Retornando as informações solicitadas
        return response()->json([
            'success' => true,
            'data' => [
                'status_caixa' => $caixaAberto->status,
                'total_vendas' => $totalVendasEmReais,
                'total_vendas_dinheiro' => $totalVendasDinheiroEmReais,  // Total de vendas realizadas em dinheiro
                'valor_abertura' => $valorAberturaEmReais,
                'total_entradas' => $totalEntradasEmReais,
                'total_saidas' => $totalSaidasEmReais,
            ],
        ], 200);
    }




    public function destroy($id)
    {
        try {
            // Obter o usuário autenticado
            $user = Auth::user(); // Usando o Auth corretamente, com a notação adequada

            // Verificar a empresa associada ao usuário autenticado
            $empresaId = $user->empresa_id;

            // Buscar a venda com o ID e da empresa correta
            $venda = Venda::where('id', $id)
                          ->where('empresa_id', $empresaId)  // Verifica se a venda pertence à empresa do usuário
                          ->firstOrFail(); // Lança uma exceção se a venda não for encontrada

            // Excluir a venda
            $venda->delete();

            return response()->json(['success' => true, 'message' => 'Venda excluída com sucesso.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao excluir a venda.', 'error' => $e->getMessage()], 500);
        }
    }

    public function adicionarOperacao(Request $request)
    {
        // Validação dos dados recebidos
        $validatedData = $request->validate([
            'tipo' => 'required|in:entrada,saida',
            'valor' => 'required|numeric|min:0.01',
            'descricao' => 'nullable|string|max:255',
        ]);

        // Verifica se o usuário está autenticado e se o ID da empresa é válido
        $userEmpresaId = Auth::user()->empresa_id;
        if (!$userEmpresaId) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não pertence a uma empresa válida.',
            ], 403);
        }

        // Busca o caixa aberto para a empresa do usuário
        $caixaAberto = CaixaMovimento::where('empresa_id', $userEmpresaId)
            ->where('status', 'aberto')
            ->first();

        if (!$caixaAberto) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhum caixa aberto encontrado para a sua empresa.',
            ], 404);
        }

        // Criação da operação de caixa
        $operacao = new CaixaOperacao();
        $operacao->empresa_id = $userEmpresaId;
        $operacao->caixa_movimento_id = $caixaAberto->id;
        $operacao->tipo = $validatedData['tipo'];
        $operacao->valor = $validatedData['valor'] * 100; // Armazena em centavos, se necessário
        $operacao->descricao = $validatedData['descricao'];
        $operacao->data_hora = now();

        // Salva a operação no banco
        if ($operacao->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Operação adicionada com sucesso!',
                'data' => $operacao,
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao adicionar operação.',
            ], 500);
        }
    }



}
