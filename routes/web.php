<?php

use App\Http\Controllers\AdminControllerEmpresas;
use App\Http\Controllers\AssinaturaController;
use App\Http\Controllers\CaixaBaocao;
use App\Http\Controllers\CaixaMovimentoController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\HistoricoVendasController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Hause/HomePage', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),

    ]);
});

Route::post('/company/store', [CompanyController::class, 'store'])->name('company.store');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard/Index');
    })->name('dashboard');

    Route::get('/historico', function () {
        return inertia::render('Historico/Index');
    })->name('historico');

    Route::get('/ajustes', function () {
        return inertia::render('Ajustes/Index');
    })->name('ajustes');

    Route::get('/tabela', function () {
        return inertia::render('Tabela/Index');
    })->name('tabela');

    // Admin
    Route::get('/admin/dashboard', function () {
        // Verificar se o usuário é admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            return Inertia::render('Admin/Dashboard/Index');
        }

        // Se não for admin, redireciona ou exibe uma mensagem
        return redirect('/')->with('error', 'Acesso restrito para administradores');
    })->name('admin.dashboard');

    Route::get('/admin/assinaturas', function () {
        // Verificar se o usuário é admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            return Inertia::render('Admin/Assinaturas/Index');
        }

        // Se não for admin, redireciona ou exibe uma mensagem
        return redirect('/')->with('error', 'Acesso restrito para administradores');
    })->name('admin.assinaturas');

    Route::get('/admin/clientes', function () {
        // Verificar se o usuário é admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            return Inertia::render('Admin/Clientes/Index');
        }

        // Se não for admin, redireciona ou exibe uma mensagem
        return redirect('/')->with('error', 'Acesso restrito para administradores');
    })->name('admin.clientes');


    // API Admin
    Route::get('/api/empresas', [AdminControllerEmpresas::class, 'listarEmpresas'])->name('empresas.listar');
    Route::get('/api/empresas/detalhes/{empresaId}', [AdminControllerEmpresas::class, 'detalhes'])->name('empresas.detalhes');
    Route::delete('/api/empresas/deletar/{empresaId}', [AdminControllerEmpresas::class, 'deletar'])->name('empresas.deletar');

    // API Admin - Assinaturas
    Route::get('/api/admin/assinaturas', [AssinaturaController::class, 'index'])->name('admin.assinaturas.listar');



    // fim Admin


    // API Produters
    Route::get('/api/products', [CaixaBaocao::class, 'fetchProducts'])->name('products.fetch');
    Route::get('/api/paymentMethod', [PaymentMethodController::class, 'apiPaymentMethods'])->name('paymentMethod.fetch');


    // Rotas dos Produtos
    Route::get('/produtos', [ProductController::class, 'index'])->name('produtos');
    Route::post('/produtos/adicionar', [ProductController::class, 'store'])->name('produto.adicionar');
    Route::post('/produto/{id}/update', [ProductController::class, 'update'])->name('produto.update');
    Route::post('/produtos/excluir', [ProductController::class, 'destroy'])->name('produto.deletar');

    Route::get('/products/images', [ProductImageController::class, 'index']);

    // Categoria
    Route::post('/categoria/adicionar', [CategoryController::class, 'store'])->name('categoria.adicionar');

    // Fomrm6a pagamentos
    Route::get('/forma/pagamentos', [PaymentMethodController::class, 'index'])->name('formas_pagamento');
    Route::post('/pagamentos/adicionar', [PaymentMethodController::class, 'store'])->name('formas_pagamento.adicionar');
    Route::delete('/pagamentos/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('paymentMethod.destroy');


    //  Caixa
    Route::get('/balcao/{mesa_id}', [CaixaBaocao::class, 'index'])->name('balcao');

    //  Messas
    Route::get('/mesas', [MesaController::class, 'index'])->name('mesas');
    Route::post('/mesas/{id}/abrir', [MesaController::class, 'abrir'])->name('mesas.abrir');

    // API Mesas
    Route::get('/api/mesas/listar', [MesaController::class, 'listarMesas'])->name('mesas.listar');
    Route::post('/api/vendas/salvar', [MesaController::class, 'salvarOuAtualizarVenda'])->name('salvaOuAtualizaVenda');
    Route::get('/api/vendas/{mesaId}', [MesaController::class, 'detalhesVenda'])->name('vendas.detalhes');
    Route::post('/api/vendas/finalizar/{mesaId}', [MesaController::class, 'finalizarVenda'])->name('vendas.finalizar');



    // Alterar nome da mesa
    Route::put('/mesas/{id}/alterar-nome', [MesaController::class, 'alterarNome'])->name('mesas.alterar-nome');


    Route::delete('/api/vendas/{id}/excluir', [MesaController::class, 'excluirVenda'])->name('vendas.excluir');


    // Historico
    Route::get('/api/historico/vendas', [MesaController::class, 'historicoDeVendas'])->name('historico.vendas');

    Route::get('/api/historico/caixa', [HistoricoVendasController::class, 'obterDadosCaixa'])->name('historico.caixa');
    Route::post('/api/caixa/operacoes', [HistoricoVendasController::class, 'adicionarOperacao'])->name('caixa.operacoes');
    Route::get('/api/vendas/detalhes/{id}', [HistoricoVendasController::class, 'show'])->name('vendas.show');

    Route::delete('/api/vendas/deletar/{id}', [HistoricoVendasController::class, 'destroy'])->name('vendas.destroy');




    // Operações de caixa
    // Verificar status do caixa
    Route::get('/api/caixa/status', [CaixaMovimentoController::class, 'verificarStatusCaixa'])->name('caixa.status');



    // Abrir caixa
    Route::post('/api/caixa/abrir', [CaixaMovimentoController::class, 'abrirCaixa'])->name('caixa.abrir');
    // Fechar caixa
    Route::post('/api/caixa/fechar', [CaixaMovimentoController::class, 'fecharCaixa'])->name('caixa.fechar');
});
