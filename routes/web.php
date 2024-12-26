<?php

use App\Http\Controllers\CaixaBaocao;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Application;
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


        // ajax
        Route::get('/api/products', [CaixaBaocao::class, 'fetchProducts'])->name('products.fetch');
        Route::get ('/api/paymentMethod', [PaymentMethodController::class, 'apiPaymentMethods'])->name('paymentMethod.fetch');

        // Rotas dos Produtos
        Route::get('/produtos', [ProductController::class, 'index'])->name('produtos');
        Route::post('/produtos/adicionar', [ProductController::class, 'store'])->name('produto.adicionar');
        Route::post('/produto/{id}/update', [ProductController::class, 'update'])->name('produto.update');
        Route::post('/produtos/excluir', [ProductController::class, 'destroy'])->name('produto.deletar');

        Route::get('/products/images', [ProductImageController::class, 'index']);

        // Categoria
        Route::post('/categoria/adicionar', [CategoryController::class, 'store'])->name('categoria.adicionar');

        // Fomra pagamentos
        Route::get('/forma/pagamentos', [PaymentMethodController::class, 'index'])->name('formas_pagamento');
        Route::post('/pagamentos/adicionar', [PaymentMethodController::class, 'store'])->name('formas_pagamento.adicionar');
        Route::delete('/pagamentos/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('paymentMethod.destroy');


        //  Caixa
        Route::get('/balcao/{mesa_id}', [CaixaBaocao::class, 'index'])->name('balcao');

        //  Messas
        Route::get('/mesas', [MesaController::class, 'index'])->name('mesas');
        Route::post('/mesas/{id}/abrir', [MesaController::class, 'abrir'])->name('mesas.abrir');






});

