<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Traits\HandlesImageUploads;

use App\Models\Categorie;
use App\Models\Product;
use App\Models\Product_variation;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;


class ProductController extends Controller
{
    use HandlesImageUploads;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $empresaId = $user->empresa_id;

        // Definindo a quantidade de itens por página
        $perPage = $request->get('per_page', 20);

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

        return Inertia::render('Produtos/Index', [
            'products' => $products,
            'categories' => $categories, // Envia apenas as categorias do `empresa_id` do usuário
            'filters' => $request->only(['search', 'category', 'sort_by', 'sort_direction']), // Passando os filtros e ordenação para o Vue
        ]);
    }

    /**
     * Display a listing of the resource (API version).
     */

     public function store(Request $request)
     {
         Log::info('Início do método store.');

         // Verifica autenticação do usuário
         if (!Auth::check()) {
             Log::warning('Usuário não autenticado tentou acessar o método store.');
             return back()->with('flash', [
                 'message' => 'Usuário não autenticado.',
                 'type' => 'error',
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
                 'barcode' => 'nullable|numeric',
                 'stock_quantity' => 'required|integer|min:0',
                 'min_stock' => 'nullable|integer',
                 'image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
             ]);

             Log::info('Dados validados com sucesso.', ['validated_data' => $validated]);
         } catch (\Throwable $e) {
             Log::error('Erro na validação dos dados.', ['error' => $e->getMessage()]);
             return back()->with('flash', [
                 'message' => 'Erro na validação dos dados: ' . $e->getMessage(),
                 'type' => 'error',
             ]);
         }

         // Verifica se a imagem já está registrada (caso tenha sido informada uma imagem)
         $imagePath = null; // Inicializa a variável

         if ($request->hasFile('image')) {
             // Se houver um arquivo de imagem, processa a imagem
             $imageName = $request->file('image')->getClientOriginalName();

             // Extrai o nome do arquivo sem o diretório, no PHP
             $imageNameOnly = pathinfo($imageName, PATHINFO_BASENAME);

             // Verifica se a imagem já existe no banco
             $existingImage = ProductImage::where('image_url', 'like', '%'.$imageNameOnly)->first();

             if ($existingImage) {
                 // Se a imagem já existe, reutiliza o caminho existente
                 $imagePath = $existingImage->image_url;
                 Log::info('Imagem já existe, reutilizando: ' . $imagePath);
             } else {
                 // Se a imagem não existe, faz o upload
                 $imagePath = $this->handleImageUpload($request);
                 if ($imagePath === false) {
                     return back()->with('flash', [
                         'message' => 'Erro ao salvar a imagem.',
                         'type' => 'error',
                     ]);
                 }
             }
         }

         // Criação do produto
         try {
             $product = Product::create([
                 'name' => $validated['name'],
                 'category_id' => $validated['category_id'],
                 'price' => $validated['price'],
                 'cost_price' => $validated['cost_price'],
                 'barcode' => $validated['barcode'],
                 'stock_quantity' => $validated['stock_quantity'],
                 'min_stock' => $validated['min_stock'],
                 'image_path' => $imagePath,
                 'empresa_id' => $empresaId,
             ]);

             Log::info('Produto criado com sucesso.', ['product_id' => $product->id]);

             // Registrar a imagem na tabela product_images (caso haja uma imagem)
             if ($imagePath) {
                 ProductImage::create([
                     'product_name' => $validated['name'],
                     'image_url' => $imagePath, // A URL da imagem ou caminho do arquivo
                 ]);

                 Log::info('Imagem registrada na tabela product_images.', ['product_id' => $product->id, 'image_url' => $imagePath]);
             }
         } catch (\Throwable $e) {
             Log::error('Erro ao criar o produto.', ['error' => $e->getMessage()]);
             return redirect()->route('home')->with('flash', [
                 'message' => 'Erro ao criar o produto: ' . $e->getMessage(),
                 'type' => 'error',
             ]);
         }

         // Resposta de sucesso
         Log::info('Produto cadastrado com sucesso.', ['product' => $product]);

         return back()->with('flash', [
             'message' => 'Processo para ' . $validated['name'] . ' concluído com sucesso!',
             'type' => 'success',
         ]);
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
        Log::info('Início do método update.');

        // Verifica autenticação do usuário
        if (!Auth::check()) {
            Log::warning('Usuário não autenticado tentou acessar o método update.');
            return back()->with('flash', [
                'message' => 'Usuário não autenticado.',
                'type' => 'error', // Tipo de mensagem de erro
            ]);
        }

        $user = Auth::user();
        $empresaId = $user->empresa_id;

        Log::info('Usuário autenticado.', ['user_id' => $user->id, 'empresa_id' => $empresaId]);

        // Encontre o produto existente
        $product = Product::find($id);

        if (!$product) {
            Log::warning('Produto não encontrado.', ['product_id' => $id]);
            return back()->with('flash', [
                'message' => 'Produto não encontrado.',
                'type' => 'error',
            ]);
        }

        // Verificar se o produto pertence à empresa do usuário
        if ($product->empresa_id !== $empresaId) {
            Log::warning('Tentativa de atualização de produto de outra empresa.', ['product_id' => $id]);
            return back()->with('flash', [
                'message' => 'Produto não pertence à sua empresa.',
                'type' => 'error',
            ]);
        }

        // Validação dos dados recebidos
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'price' => 'required|numeric',
                'cost_price' => 'nullable|numeric',
                'barcode' => 'nullable|numeric',
                'stock_quantity' => 'required|integer|min:0',
                'min_stock' => 'nullable|integer',
                'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:4048',
            ]);

            Log::info('Dados validados com sucesso.', ['validated_data' => $validated]);
        } catch (\Throwable $e) {
            Log::error('Erro na validação dos dados.', ['error' => $e->getMessage()]);
            return back()->with('flash', [
                'message' => 'Erro na validação dos dados: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }

        // Verifica se uma nova imagem foi enviada
        if ($request->hasFile('image')) {
            $product->image_path = $this->handleImageUpload($request, 'image');
        } elseif ($request->input('current_image_path')) {
            // Mantém o caminho atual se nenhuma nova imagem for enviada
            $product->image_path = $request->input('current_image_path');
        }

        // Atualização do produto
        try {
            $product->update([
                'name' => $validated['name'],
                'category_id' => $validated['category_id'],
                'price' => $validated['price'],
                'cost_price' => $validated['cost_price'],
                'barcode' => $validated['barcode'],
                'stock_quantity' => $validated['stock_quantity'],
                'min_stock' => $validated['min_stock'],
            ]);

            Log::info('Produto atualizado com sucesso.', ['product_id' => $product->id]);
        } catch (\Throwable $e) {
            Log::error('Erro ao atualizar o produto.', ['error' => $e->getMessage()]);
            return redirect()->route('home')->with('flash', [
                'message' => 'Erro ao atualizar o produto: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }

        // Resposta de sucesso
        Log::info('Produto atualizado com sucesso.', ['product' => $product]);

        return back()->with('flash', [
            'message' => 'Produto ' . $validated['name'] . ' atualizado com sucesso!',
            'type' => 'success',
        ]);
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

        // Busca os produtos a serem excluídos
        $products = Product::whereIn('id', $ids)
            ->where('empresa_id', $empresaId)
            ->get();

        if ($products->isEmpty()) {
            return back()->with([
                'message' => 'Nenhum produto encontrado ou você não tem permissão para excluí-los.',
                'type' => 'error',
            ]);
        }

        // Coletar os nomes dos produtos para a mensagem de feedback
        $productNames = $products->pluck('name')->toArray();

        foreach ($products as $product) {
            // Verifica se o produto tem uma imagem associada e exclui a imagem
            if ($product->image_path && Storage::exists($product->image_path)) {
                Storage::delete($product->image_path);
            }

            // Excluir o produto
            $product->delete();
        }

        // Retorna uma mensagem personalizada
        return back()->with('flash', [
            'message' => count($productNames) === 1
                ? "O produto '{$productNames[0]}' foi excluído com sucesso."
                : count($productNames) . ' produtos foram excluídos com sucesso.',
            'type' => 'success',
        ]);
    }






}
