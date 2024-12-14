<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

trait HandlesImageUploads
{
    /**
     * Faz o upload de uma imagem e retorna o caminho armazenado.
     *
     * @param Request $request
     * @param string $inputName
     * @param string $directory
     * @return string|null
     */
    public function handleImageUpload(Request $request, $inputName = 'image', $directory = 'images')
    {
        if ($request->hasFile($inputName)) {
            try {
                // Realiza o upload da imagem
                $imagePath = $request->file($inputName)->store($directory, 'public');

                // Log do sucesso
                Log::info('Imagem salva com sucesso.', ['image_path' => $imagePath]);

                return $imagePath; // Retorna o caminho da imagem
            } catch (\Throwable $e) {
                // Log de erro
                Log::error('Erro ao salvar a imagem.', ['error' => $e->getMessage()]);

                throw new \Exception('Erro ao salvar a imagem.'); // Lança a exceção para lidar no controller
            }
        }

        return null; // Retorna nulo caso nenhuma imagem seja enviada
    }
}
