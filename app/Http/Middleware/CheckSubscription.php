<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $empresa = Auth::user()->empresa;

            // Verifica se a assinatura está expirada
            if ($empresa->assinatura && $empresa->assinatura->fim) {
                $fimAssinatura = Carbon::parse($empresa->assinatura->fim);

                // Se a assinatura expirou e o usuário não está na página de planos
                if ($fimAssinatura->isPast() && !Route::is('planos')) {
                    // Se a assinatura expirou, defina a variável de sessão
                    session()->put('subscriptionExpired', true);

                    // Redireciona para a página de planos
                    return redirect()->route('planos');
                }
            }
        }

        return $next($request);
    }
}
