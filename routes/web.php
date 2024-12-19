<?php

use App\Http\Controllers\CaixaBaocao;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductImageController;
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

        //  Caixa
        Route::get('/balcao', [CaixaBaocao::class, 'index'])->name('balcao');
        // ajax
        Route::get('/api/products', [CaixaBaocao::class, 'fetchProducts'])->name('products.fetch');

        // Rotas dos Produtos
        Route::get('/produtos', [ProductController::class, 'index'])->name('produtos');
        Route::post('/produtos/adicionar', [ProductController::class, 'store'])->name('produto.adicionar');
        Route::post('/produto/{id}/update', [ProductController::class, 'update'])->name('produto.update');
        Route::post('/produtos/excluir', [ProductController::class, 'destroy'])->name('produto.deletar');

        Route::get('/products/images', [ProductImageController::class, 'index']);


        // Categoria
        Route::post('/categoria/adicionar', [CategoryController::class, 'store'])->name('categoria.adicionar');





});

