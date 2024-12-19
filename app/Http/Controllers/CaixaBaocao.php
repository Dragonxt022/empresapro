<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Categorie;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CaixaBaocao extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $empresaId = $user->empresa_id;

        // Definindo a quantidade de itens por página
        $perPage = $request->get('per_page', 30);

        // Parâmetros de ordenação
        $sortBy = $request->get('sort_by', 'name'); // Valor padrão: 'name'
        $sortDirection = $request->get('sort_direction', 'desc'); // Valor padrão: 'desc'

        // Verificando se a direção de ordenação é válida ('asc' ou 'desc')
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc'; // Direção padrão
        }

        // Inicia a consulta com os relacionamentos necessários
        $query = Product::with(['variations', 'category'])
                        ->where('empresa_id', $empresaId);

        // Filtrando os dados com base nos parâmetros de pesquisa
        $query->where(function($q) use ($request) {
            if ($request->has('search') && $request->search != '') {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('sku', 'like', '%' . $request->search . '%');
            }

            if ($request->has('category') && $request->category != '') {
                $q->whereHas('category', function($subQuery) use ($request) {
                    $subQuery->where('name', $request->category);
                });
            }
        });

        // Ordenando os resultados
        $products = ProductResource::collection($query->orderBy($sortBy, $sortDirection)->paginate($perPage));
        // Recuperando as categorias filtradas pela empresa do usuário
        $categories = Categorie::where('empresa_id', $empresaId)->get();

        return Inertia::render('Caixa/Index', [
            'products' => $products,
            'categories' => $categories, // Envia apenas as categorias do `empresa_id` do usuário
            'filters' => $request->only(['search', 'category', 'sort_by', 'sort_direction']), // Passando os filtros e ordenação para o Vue
        ]);
    }

    public function fetchProducts(Request $request)
    {
        Log::info('Rota fetchProducts foi chamada');
        $user = Auth::user();
        $empresaId = $user->empresa_id;

        $perPage = $request->get('per_page', 30);
        $sortBy = $request->get('sort_by', 'name');
        $sortDirection = $request->get('sort_direction', 'desc');
        $category = $request->get('category', null);

        $query = Product::with('category')
            ->where('empresa_id', $empresaId);

        if ($category) {
            $query->where('category_id', $category);
        }

        $products = $query->orderBy($sortBy, $sortDirection)->paginate($perPage);

        Log::info('Produtos retornados:', ['products' => $products]);

        $products->withPath('/api/products');

        return response()->json($products);
    }


}
