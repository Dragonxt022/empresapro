<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\ProductImage;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    use HandlesImageUploads;

    public function store(Request $request)
    {
        // Obtém o usuário autenticado
        $user = Auth::user();

        // Valida os dados recebidos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:5048',
        ]);

        // Inicializa a variável para o caminho da imagem
        $imagePath = null;

        // Verifica se foi enviado um arquivo de imagem
        if ($request->hasFile('image')) {
            $imageName = $request->file('image')->getClientOriginalName();
            $imageNameOnly = pathinfo($imageName, PATHINFO_BASENAME);

            // Verifica se a imagem já está registrada no banco de dados
            $existingImage = ProductImage::where('image_url', 'like', '%' . $imageNameOnly)->first();

            if ($existingImage) {
                // Se a imagem já existe, reutiliza o caminho existente
                $imagePath = $existingImage->image_url;
                Log::info('Imagem já existe, reutilizando: ' . $imagePath);
            } else {
                // Se a imagem não existe, faz o upload
                $imagePath = $this->handleImageUpload($request);
                if ($imagePath === false) {
                    // Se houve erro ao salvar a imagem
                    return back()->with('flash', [
                        'message' => 'Erro ao salvar a imagem.',
                        'type' => 'error',
                    ]);
                }
            }
        }

        // Cria a nova categoria
        $category = Categorie::create([
            'name' => $validated['name'],
            'image_path' => $imagePath,
            'empresa_id' => $user->empresa_id,
            'is_active' => true,
        ]);

        // Se a categoria foi criada com sucesso e há uma imagem
        if ($imagePath) {
            // Registra a imagem na tabela product_images
            ProductImage::create([
                'product_name' => $validated['name'],
                'image_url' => $imagePath, // Caminho da imagem
            ]);

            Log::info('Imagem registrada na tabela product_images.', ['category_id' => $category->id, 'image_url' => $imagePath]);
        }

        // Resposta de sucesso
        return back()->with('flash', [
            'message' => 'Categoria ' . $validated['name'] . ' cadastrada com sucesso!',
            'type' => 'success',
        ]);
    }
}
