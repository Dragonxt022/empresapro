<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Verifica se o usuário é admin
        if (Auth::check() && Auth::user()->role !== 'admin') {
            // Redireciona para a página de home ou qualquer outra página
            return redirect('/');
        }

        return $next($request);
    }
}
