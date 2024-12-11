<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProductController;
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


        // Rotas dos Produtos
        Route::get('/produtos', [ProductController::class, 'index'])->name('produtos');
        // adicionar Produtos
        Route::post('/produtos/adicionar', [ProductController::class, 'store']);
        // Excluir Produtos
        Route::post('/produtos/excluir', [ProductController::class, 'destroy']);





});

