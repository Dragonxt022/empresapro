<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Categorie;
use App\Models\Mesa;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    /**
     * Show the form for creating a new resource.
     */
    public function abrir($id)
    {
        $mesa = Mesa::findOrFail($id);

        // Atualiza o status da mesa
        $mesa->status = 'aberto';
        $mesa->save();

        // Redireciona para a rota 'balcao' com o ID da mesa
        return redirect()->route('balcao', ['mesa_id' => $mesa->id]);
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
