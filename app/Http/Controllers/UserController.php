<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    public function profile()
    {
        $user = Auth::user();

        // Caso precise adicionar dados adicionais, faça aqui.
        return Inertia::render('Ajustes/Index', [
            'user' => [
                'id' => $user->id,
                'first_name' => $user->first_name,
                'email' => $user->email,
                'image' => $user->image_path ?? 'default-profile-image.jpg', // Caminho da imagem
            ],
        ]);
    }
}
