<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Categorie;
use App\Models\Mesa;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class MesaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $empresaId = $user->empresa_id;

        // Busca as mesas da empresa do usuário autenticado
        $mesas = Mesa::where('empresa_id', $empresaId)->get();

        // Definindo a quantidade de itens por página
        $perPage = $request->get('per_page', 30);

        // Lógica de ordenação
        $sortBy = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'desc');
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        // Consulta para buscar produtos
        $query = Product::with(['variations', 'category'])
            ->where('empresa_id', $empresaId)
            ->when($request->search, function ($q, $search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('sku', 'like', '%' . $search . '%');
            })
            ->when($request->category, function ($q, $category) {
                $q->whereHas('category', function ($subQuery) use ($category) {
                    $subQuery->where('name', $category);
                });
            });

        $products = ProductResource::collection($query->orderBy($sortBy, $sortDirection)->paginate($perPage));
        $categories = Categorie::where('empresa_id', $empresaId)->get();

        // Renderiza a página com os dados usando o Inertia
        return Inertia::render('Mesa/Index', [
            'mesas' => $mesas,
            'products' => $products, // Lista de produtos
            'categories' => $categories, // Categorias para filtro
            'filters' => $request->only(['search', 'category', 'sort_by', 'sort_direction']),

        ]);
    }

    public function listarMesas(Request $request)
    {
        try {
            $user = Auth::user();
            $empresaId = $user->empresa_id;

            // Buscar mesas da empresa do usuário autenticado com informações adicionais
            $mesas = Mesa::where('empresa_id', $empresaId)
                ->with(['vendas' => function ($query) {
                    $query->where('status', 'pendente')
                        ->select('mesa_id', 'created_at', 'valor_total');
                }])
                ->select('id', 'nome', 'status') // Retornar apenas os campos necessários
                ->get()
                ->map(function ($mesa) {
                    $vendaPendente = $mesa->vendas->first(); // Assume apenas uma venda pendente por mesa
                    $tempoAberta = $vendaPendente ? now()->diffForHumans($vendaPendente->created_at, true) : null;

                    return [
                        'id' => $mesa->id,
                        'nome' => $mesa->nome,
                        'status' => $mesa->status,
                        'venda' => $vendaPendente ? [
                            'created_at' => $vendaPendente->created_at->format('Y-m-d H:i:s'),
                            'valor_total' => $vendaPendente->valor_total,
                            'valor_total_formatado' => 'R$ ' . number_format($vendaPendente->valor_total / 100, 2, ',', '.'),
                            'tempo_aberta' => $tempoAberta, // Tempo desde que a mesa foi aberta
                        ] : null,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $mesas,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao listar mesas.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function abrir($id)
    {
        $mesa = Mesa::findOrFail($id);

        // Atualiza o status da mesa
        $mesa->status = 'aberto';
        $mesa->save();

        return join([
            'success' => true,
            'message' => 'Mesa aberta com sucesso!',
        ]);
    }


    public function salvarOuAtualizarVenda(Request $request)
    {
        $validated = $request->validate([
            'mesaId' => 'nullable|integer|exists:mesas,id',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|integer |exists:products,id',
            'products.*.name' => 'required|string',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.totalPrice' => 'required|integer|min:0',
            'payments' => 'nullable|array',
            'payments.*.name' => 'nullable|string',
            'payments.*.amount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Verifica se já existe uma venda pendente para a mesa
            $venda = \App\Models\Venda::where('mesa_id', $validated['mesaId'] ?? null)
                ->where('empresa_id', Auth::user()->empresa_id)
                ->where('status', 'pendente')
                ->first();

            // Se não existir, cria uma nova
            if (!$venda) {
                $venda = new \App\Models\Venda();
                $venda->mesa_id = $validated['mesaId'] ?? null;
                $venda->empresa_id = Auth::user()->empresa_id;
                $venda->vendido_por = Auth::id();
                $venda->status = 'pendente';
            }

            // Associa a venda ao caixa aberto, se houver
            $caixaAberto = \App\Models\CaixaMovimento::where('empresa_id', Auth::user()->empresa_id)
                ->where('status', 'aberto')
                ->first();

            if ($caixaAberto) {
                $venda->caixa_movimento_id = $caixaAberto->id;
            }

            // Atualiza os dados básicos da venda
            $venda->valor_total = $validated['total'];
            $venda->save();

            foreach ($validated['products'] as $product) {
                // Verifica se o produto existe antes de associar
                $produtoExistente = Product::find($product['id']);
                if (!$produtoExistente) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Produto não encontrado: ' . $product['id']
                    ], 400);
                }

                // Usando updateOrCreate para evitar duplicação de produtos na venda
                $venda->produtos()->updateOrCreate(
                    ['product_id' => $product['id']], // Chave de busca para verificar se o produto já existe
                    [
                        'nome' => $product['name'],
                        'valor_unitario' => $product['totalPrice'] / $product['quantity'],
                        'quantidade' => $product['quantity'],
                        'valor_total' => $product['totalPrice'],
                    ]
                );
            }



            // Atualiza os pagamentos
            $venda->pagamentos()->delete();
            if (!empty($validated['payments'])) {
                foreach ($validated['payments'] as $payment) {
                    $venda->pagamentos()->create([
                        'metodo' => $payment['name'],
                        'valor' => $payment['amount'],
                    ]);
                }
            }

            // Atualiza o status da mesa, se aplicável
            if ($validated['mesaId']) {
                $mesa = \App\Models\Mesa::find($validated['mesaId']);
                if ($mesa) {
                    $mesa->status = 'pendente';
                    $mesa->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Venda salva ou atualizada com sucesso!',
                'venda_id' => $venda->id
            ], $venda->wasRecentlyCreated ? 201 : 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => 'Erro ao salvar ou atualizar venda: ' . $e->getMessage()
            ], 500);
        }
    }


    public function finalizarVenda(Request $request, $mesaId)
    {
        $validated = $request->validate([
            'mesaId' => 'required|integer|exists:mesas,id',
            'payments' => 'required|array|min:1',
            'payments.*.name' => 'required|string',
            'payments.*.amount' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $mesaId = $validated['mesaId'];

            // Busca a venda pendente
            $venda = \App\Models\Venda::with('produtos')
                ->where('mesa_id', $mesaId)
                ->where('empresa_id', Auth::user()->empresa_id)
                ->where('status', 'pendente')
                ->first();

            if (!$venda) {
                throw new \Exception('Venda pendente não encontrada.');
            }

            // Associa a venda ao caixa, caso haja um caixa aberto
            $caixaAberto = \App\Models\CaixaMovimento::where('empresa_id', Auth::user()->empresa_id)
                ->where('status', 'aberto')
                ->first();

            if ($caixaAberto) {
                $venda->caixa_movimento_id = $caixaAberto->id;
            }

            // Adiciona os pagamentos
            $venda->pagamentos()->delete();  // Exclui os pagamentos anteriores, caso existam

            // Cria os novos pagamentos
            foreach ($validated['payments'] as $payment) {
                $venda->pagamentos()->create([
                    'venda_id' => $venda->id,
                    'metodo' => $payment['name'],
                    'valor' => $payment['amount'],
                ]);
            }

            // Atualiza o status da venda para "finalizada"
            $venda->status = 'finalizada';
            $venda->save();

            // Atualiza o status da mesa
            $mesa = \App\Models\Mesa::find($mesaId);
            if ($mesa) {
                $mesa->status = 'livre';  // Marca a mesa como livre
                $mesa->save();
            }

            // Atualiza o estoque dos produtos vendidos
            foreach ($venda->produtos as $produto) {
                $produtoEstoque = \App\Models\Product::find($produto->id);
                if ($produtoEstoque) {
                    // Permite estoque negativo
                    $produtoEstoque->stock_quantity -= $produto->quantidade;
                    $produtoEstoque->save();
                }
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Venda finalizada com sucesso!'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Erro ao finalizar a venda.', 'error' => $e->getMessage()], 500);
        }
    }


    // No seu controlador (MesaController ou VendaController)
    public function detalhesVenda($mesaId)
    {
        try {
            // Identificando o usuário autenticado e sua empresa
            $user = Auth::user();
            $empresaId = $user->empresa_id;

            // Buscando a venda pelo ID da mesa e com status pendente na empresa do usuário
            $venda = \App\Models\Venda::with(['produtos', 'pagamentos'])
                ->where('mesa_id', $mesaId) // ID da mesa
                ->where('empresa_id', $empresaId) // Empresa do usuário
                ->where('status', 'pendente') // Status da venda
                ->first();

            // Verificando se a venda existe
            if (!$venda) {
                return response()->json([
                    'success' => false,
                    'message' => 'Venda não encontrada ou já finalizada.',
                ], 404);
            }

            // Retornando os dados da venda
            return response()->json([
                'success' => true,
                'venda' => $venda,
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

    public function excluirVenda($mesaId)
    {
        try {
            // Buscar a venda associada à mesa com status "pendente"
            $venda = Venda::where('mesa_id', $mesaId)->where('status', 'pendente')->first();

            // Buscar a mesa pelo ID
            $mesa = Mesa::findOrFail($mesaId);

            if (!$venda) {
                // Se não houver vendas pendentes, verificar se a mesa está "aberto"
                if ($mesa->status === 'aberto') {
                    // Atualizar o status da mesa para "livre"
                    $mesa->status = 'livre';
                    $mesa->save();
                    return response()->json(['success' => true, 'message' => 'Mesa liberada com sucesso (não havia vendas pendentes).']);
                }

                return response()->json(['success' => false, 'message' => 'Nenhuma venda pendente encontrada para esta mesa e ela não está aberto.'], 404);
            }

            // Atualizar o status da mesa para "livre"
            $mesa->status = 'livre';
            $mesa->save();

            // Excluir a venda
            $venda->delete();

            return response()->json(['success' => true, 'message' => 'Venda excluída e mesa liberada com sucesso!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Erro ao processar a operação: ' . $e->getMessage()], 500);
        }
    }

    // Função para alterar o nome da mesa
    public function alterarNome($id, Request $request)
    {
        // Valida o nome enviado
        $validated = $request->validate([
            'nome' => 'required|string|max:255', // Validação do nome
        ]);

        try {
            // Encontra a mesa pelo ID
            $mesa = Mesa::findOrFail($id);  // Usando 'findOrFail' para lançar uma exceção se a mesa não for encontrada

            // Atualiza o nome da mesa
            $mesa->nome = $validated['nome'];
            $mesa->save();

            // Retorna a resposta com sucesso
            return response()->json([
                'message' => 'Nome da mesa atualizado com sucesso',
                'mesa' => $mesa
            ]);
        } catch (\Exception $e) {
            // Caso ocorra algum erro
            return response()->json([
                'message' => 'Erro ao atualizar o nome da mesa'
            ], 500); // 500 é o código HTTP para erro interno do servidor
        }
    }


    public function historicoDeVendas(Request $request)
    {
        $user = Auth::user();
        $empresa = $user->empresa;

        if (!$empresa) {
            return response()->json(['message' => 'Usuário não está vinculado a nenhuma empresa'], 400);
        }

        $query = Venda::with(['produtos', 'pagamentos', 'mesa', 'empresa', 'vendedor'])
            ->where('empresa_id', $empresa->id)
            ->where('status', '!=', 'pendente');

        // Filtro por pesquisa
        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');

            $query->where(function ($query) use ($searchTerm) {
                $query->where('cliente', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('valor_total', 'LIKE', "%{$searchTerm}%")
                    ->orWhereHas('vendedor', function ($q) use ($searchTerm) {
                        $q->where('first_name', 'LIKE', "%{$searchTerm}%")
                            ->orWhere('last_name', 'LIKE', "%{$searchTerm}%");
                    });
            });
        }

        // Filtro por data
        if ($request->has('data_inicio') && $request->has('data_fim')) {
            $dataInicio = $request->input('data_inicio');
            $dataFim = $request->input('data_fim');

            // Validação básica das datas
            if (strtotime($dataInicio) > strtotime($dataFim)) {
                return response()->json(['message' => 'Data inicial não pode ser maior que a data final'], 400);
            }

            // Verifica a diferença de dias entre as datas
            $dataInicioObj = new \Carbon\Carbon($dataInicio);
            $dataFimObj = new \Carbon\Carbon($dataFim);

            // Se a diferença for maior que 90 dias, ajusta a data final para 90 dias após a data inicial
            if ($dataInicioObj->diffInDays($dataFimObj) > 90) {
                $dataFimObj = $dataInicioObj->copy()->addDays(90);
            }

            // Atualiza as datas no filtro
            $query->whereBetween('created_at', [$dataInicioObj->toDateString(), $dataFimObj->toDateString()]);
        } else {
            // Filtro padrão: vendas do dia atual
            $query->whereDate('created_at', now()->toDateString());
        }

        // Filtro por ordem
        $ordem = $request->input('ordem', 'mais_recentes'); // Default: mais recentes
        $query->orderBy('created_at', $ordem === 'mais_antigas' ? 'asc' : 'desc');

        // Recuperando os dados filtrados
        $vendas = $query->get()->map(function ($venda) {
            $vendedorNome = $venda->vendedor ? $venda->vendedor->name : 'Não informado';

            return [
                'id' => $venda->id,
                'cliente' => $venda->cliente ?? 'Não informado',
                'valor_total' => number_format($venda->valor_total / 100, 2, ',', '.'),
                'desconto' => number_format($venda->desconto / 100, 2, ',', '.'),
                'acrescimo' => number_format($venda->acrescimo / 100, 2, ',', '.'),
                'vendedor' => $vendedorNome,
                'data_venda' => $venda->created_at->format('d-m-Y / H:i:s'),
                'vendedor' => $venda->vendedor ? [
                    'first_name' => $venda->vendedor->first_name,
                    'last_name' => $venda->vendedor->last_name,
                    'full_name' => $venda->vendedor->first_name . ' ' . $venda->vendedor->last_name,
                ] : [
                    'first_name' => 'Não informado',
                    'last_name' => '',
                    'full_name' => 'Não informado',
                ],
                'produtos' => $venda->produtos->map(function ($produto) {
                    return [
                        'nome' => $produto->nome,
                        'quantidade' => $produto->quantidade,
                        'valor_unitario' => number_format($produto->valor_unitario / 100, 2, ',', '.'),
                        'valor_total' => number_format($produto->valor_total / 100, 2, ',', '.')
                    ];
                }),
                'pagamentos' => $venda->pagamentos->map(function ($pagamento) {
                    $metodoPagamento = PaymentMethod::find($pagamento->metodo);
                    return [
                        'forma_pagamento' => $metodoPagamento ? $metodoPagamento->name : 'Não informado',
                        'valor' => number_format($pagamento->valor / 100, 2, ',', '.')
                    ];
                })
            ];
        });

        return response()->json($vendas);
    }

    // Controle de Criação de Mesas
    public function alterarMesas(Request $request)
    {
        // Validação do número de mesas
        $validator = Validator::make($request->all(), [
            'num_mesas' => 'required|integer|min:1|max:100', // Número de mesas entre 1 e 100
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'É necessário fornecer um número válido de mesas (mínimo 1 e máximo 100).'
            ], 400);
        }

        $numMesasDesejadas = $request->num_mesas;

        // Obter o ID da empresa do usuário autenticado
        $empresaId = Auth::user()->empresa_id;

        // Obter todas as mesas existentes para a empresa do usuário autenticado
        $mesasExistentes = Mesa::where('empresa_id', $empresaId)->get();

        // Obter as mesas ocupadas
        $mesasOcupadas = $mesasExistentes->where('status', 'ocupada');

        // Caso o número de mesas seja maior, gerar novas mesas
        if ($numMesasDesejadas > $mesasExistentes->count()) {
            // Criar novas mesas até atingir o limite de 100 mesas
            $quantidadeParaAdicionar = $numMesasDesejadas - $mesasExistentes->count();

            if ($mesasExistentes->count() + $quantidadeParaAdicionar > 100) {
                return response()->json([
                    'success' => false,
                    'message' => 'O limite de 100 mesas foi atingido. Não é possível criar mais mesas.'
                ], 400);
            }

            for ($i = 0; $i < $quantidadeParaAdicionar; $i++) {
                Mesa::create([
                    'empresa_id' => $empresaId,
                    'nome' => 'Mesa ' . ($mesasExistentes->count() + $i + 1), // Nome para nova mesa
                    'status' => 'livre',
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => "$quantidadeParaAdicionar novas mesas foram geradas com sucesso!"
            ]);
        }

        // Caso o número de mesas seja menor, verificar as mesas ocupadas
        if ($numMesasDesejadas < $mesasExistentes->count()) {
            // Calcular quantas mesas precisam ser removidas
            $quantidadeRemover = $mesasExistentes->count() - $numMesasDesejadas;

            // Verificar se há mesas ocupadas entre as que serão removidas
            $mesasRemover = $mesasExistentes->sortByDesc('nome')->take($quantidadeRemover);

            // Se houver mesas ocupadas entre as mesas que seriam removidas, não permitimos a remoção
            foreach ($mesasRemover as $mesa) {
                if ($mesa->status === 'pendente') {
                    return response()->json([
                        'success' => false,
                        'message' => "Não é possível remover mesas ocupadas."
                    ], 400);
                }
            }

            // Remover mesas livres (as mesas removidas são da última para a primeira)
            $mesasRemover->each(function ($mesa) {
                $mesa->delete();
            });

            return response()->json([
                'success' => true,
                'message' => "$quantidadeRemover mesas foram removidas com sucesso!"
            ]);
        }

        // Caso o número de mesas já seja igual ao desejado
        return response()->json([
            'success' => true,
            'message' => 'O número de mesas já está correto.'
        ]);
    }
}
