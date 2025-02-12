<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Categorie;
use App\Models\Mesa;
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

        return Inertia::render('Caixa/Index', [
            'products' => $products, // Lista de produtos
            'categories' => $categories, // Categorias para filtro
            'filters' => $request->only(['search', 'category', 'sort_by', 'sort_direction']),
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
