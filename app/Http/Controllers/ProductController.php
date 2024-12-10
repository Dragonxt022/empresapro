<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Categorie;
use App\Models\Product;
use App\Models\Product_variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $empresaId = $user->empresa_id;

        // Definindo a quantidade de itens por página
        $perPage = $request->get('per_page', 20);

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

        // Paginação
        $products = ProductResource::collection($query->paginate($perPage));

        // Recuperando as categorias para o filtro
        $categories = Categorie::all();

        return Inertia::render('Produtos/Index', [
            'products' => $products,
            'categories' => $categories,
            'filters' => $request->only(['search', 'category']), // Passando os filtros para o Vue
        ]);
    }


    public function store(Request $request)
    {
        Log::info('Início do método store.');

        // Verifica autenticação do usuário
        if (!Auth::check()) {
            Log::warning('Usuário não autenticado tentou acessar o método store.');
            return redirect()->route('home')->with('flash', [
                'banner' => 'Usuário não autenticado.',
                'bannerStyle' => 'danger', // Estilo para erro
            ]);
        }

        $user = Auth::user();
        $empresaId = $user->empresa_id;

        Log::info('Usuário autenticado.', ['user_id' => $user->id, 'empresa_id' => $empresaId]);

        // Validação dos dados recebidos
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'price' => 'required|numeric',
                'cost_price' => 'nullable|numeric',
                'stock_quantity' => 'required|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            Log::info('Dados validados com sucesso.', ['validated_data' => $validated]);
        } catch (\Throwable $e) {
            Log::error('Erro na validação dos dados.', ['error' => $e->getMessage()]);
            return redirect()->route('home')->with('flash', [
                'banner' => 'Erro na validação dos dados: ' . $e->getMessage(),
                'bannerStyle' => 'danger',
            ]);
        }

        // Manipulação da imagem, se presente
        $imagePath = $this->handleImageUpload($request);

        if ($imagePath === false) {
            return redirect()->route('home')->with('flash', [
                'banner' => 'Erro ao salvar a imagem.',
                'bannerStyle' => 'danger',
            ]);
        }

        // Criação do produto
        try {
            $product = Product::create([
                'name' => $validated['name'],
                'category_id' => $validated['category_id'],
                'price' => $validated['price'],
                'cost_price' => $validated['cost_price'],
                'stock_quantity' => $validated['stock_quantity'],
                'image_path' => $imagePath,
                'empresa_id' => $empresaId,
            ]);

            Log::info('Produto criado com sucesso.', ['product_id' => $product->id]);
        } catch (\Throwable $e) {
            Log::error('Erro ao criar o produto.', ['error' => $e->getMessage()]);
            return redirect()->route('home')->with('flash', [
                'banner' => 'Erro ao criar o produto: ' . $e->getMessage(),
                'bannerStyle' => 'danger',
            ]);
        }

        // Resposta de sucesso
        Log::info('Produto cadastrado com sucesso.', ['product' => $product]);

        return redirect()->route('home')->with('flash', [
            'banner' => 'Processo para ' . $validated['name'] . ' concluído com sucesso!',
            'bannerStyle' => 'success', // Estilo para sucesso
        ]);
    }


    /**
     * Manipula o upload da imagem.
     *
     * @param Request $request
     * @return string|false
     */
    private function handleImageUpload(Request $request)
    {
        if ($request->hasFile('image')) {
            try {
                $imagePath = $request->file('image')->store('images', 'public');
                Log::info('Imagem salva com sucesso.', ['image_path' => $imagePath]);
                return $imagePath;
            } catch (\Throwable $e) {
                Log::error('Erro ao salvar a imagem.', ['error' => $e->getMessage()]);
                return false;
            }
        }

        return null;
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Busque o produto pelo ID
        $product = Product::findOrFail($id);

        // Retorne o produto usando o ProductResource
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user(); // Pega o usuário autenticado
        $empresaId = $user->empresa_id; // Acessa o empresa_id do usuário autenticado

        // Validação dos dados recebidos
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku,' . $id,
            'price' => 'required|numeric',
            'cost_price' => 'nullable|numeric',
            'stock_quantity' => 'required|integer',
            'min_stock' => 'nullable|integer',
            'image_path' => 'nullable|url',
            'ncm_code' => 'nullable|string|max:255',
            'supplier_id' => 'required|exists:suppliers,id',
            'expiration_date' => 'nullable|date',
            'enable_variations' => 'nullable|boolean',
            'variations' => 'nullable|array',
            'variations.*.name' => 'nullable|string|max:255',
            'variations.*.sku' => 'required|string|max:255',
            'variations.*.price' => 'nullable|numeric',
            'variations.*.stock_quantity' => 'required|integer',
            'variations.*.is_active' => 'required|boolean',
            'promotion_ids' => 'nullable|array',
            'promotion_ids.*' => 'exists:promotions,id',
            'combo_ids' => 'nullable|array',
            'combo_ids.*' => 'exists:combos,id',
        ]);

        // Buscar o produto a ser atualizado
        $product = Product::findOrFail($id);

        // Atualizar o produto
        $product->update([
            'name' => $request->name,
            'sku' => $request->sku,
            'barcode' => $request->barcode,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'cost_price' => $request->cost_price,
            'stock_quantity' => $request->stock_quantity,
            'min_stock' => $request->min_stock,
            'is_active' => $request->is_active ?? 1,
            'is_managed' => $request->is_managed ?? 0,
            'image_path' => $request->image_path,
            'ncm_code' => $request->ncm_code,
            'supplier_id' => $request->supplier_id,
            'expiration_date' => $request->expiration_date,
            'empresa_id' => $empresaId, // Usando o empresa_id do usuário autenticado
        ]);

        // Atualizar promoções associadas ao produto
        if ($request->has('product_id')) {
            $product->promotions()->sync($request->promotion_ids);
        }

        // Atualizar combos associados ao produto
        if ($request->has('product_id')) {
            $product->combos()->sync($request->combo_ids);
        }

        // Atualizar variações, se ativadas
        if ($request->enable_variations && $request->has('variations')) {
            foreach ($request->variations as $variation) {
                $product->variations()->updateOrCreate(
                    ['sku' => $variation['sku']], // Identificar a variação pela SKU
                    [
                        'name' => $variation['name'],
                        'price' => $variation['price'],
                        'stock_quantity' => $variation['stock_quantity'],
                        'is_active' => $variation['is_active'] ?? true,
                    ]
                );
            }
        }

        // Resposta de sucesso
        return response()->json([
            'message' => 'Produto atualizado com sucesso!',
            'product' => $product,
        ], 200);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();
        $empresaId = $user->empresa_id;

        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return back()->with([
                'message' => 'Nenhum produto selecionado para exclusão.',
                'type' => 'error',
            ]);
        }

        $products = Product::whereIn('id', $ids)
            ->where('empresa_id', $empresaId)
            ->get();

        if ($products->isEmpty()) {
            return back()->with([
                'message' => 'Nenhum produto encontrado ou você não tem permissão para excluí-los.',
                'type' => 'error',
            ]);
        }

        Product::whereIn('id', $products->pluck('id'))->delete();

        return back()->with([
            'message' => 'Produtos excluídos com sucesso.',
            'type' => 'success',
        ]);
    }




}
