<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductImageController extends Controller
{
    public function index(Request $request)
    {
        // Captura o termo de pesquisa enviado do frontend
        $searchTerm = $request->input('term', ''); // Termo de pesquisa ou vazio se não fornecido

        Log::info('Início da pesquisa de produtos e imagens.', ['search_term' => $searchTerm]);

        try {
            // Consulta na tabela ProductImage com filtro pelo nome do produto
            $productImagesQuery = ProductImage::query();

            // Se o termo de pesquisa for fornecido, aplica o filtro
            if ($searchTerm) {
                $productImagesQuery->where('product_name', 'like', '%' . $searchTerm . '%');
            }

            // Limite de resultados e ordenação
            $productImages = $productImagesQuery->limit(20)->get(); // Limita os resultados a 20 imagens

            // Verifica se a consulta retornou imagens
            if ($productImages->isEmpty()) {
                Log::warning('Nenhum produto com imagem encontrado para o termo de pesquisa: ' . $searchTerm);
                return response()->json(['message' => 'Nenhum produto encontrado.'], 404);
            }

            // Formata a resposta
            $data = $productImages->map(function ($productImage) {
                return [
                    'title' => $productImage->product_name, // Nome do produto
                    'link' => asset('storage/' . $productImage->image_url) // Caminho completo da imagem
                ];
            });

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Erro ao listar produtos e imagens: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao listar os produtos e imagens.'], 500);
        }
    }
}

