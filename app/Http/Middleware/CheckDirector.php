<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckDirector
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Faça login para acessar esta página.');
        }

        if (Auth::user()->role !== 'director') {
            return redirect()->route('dashboard')->with('error', 'Acesso restrito a diretores.');
        }

        return $next($request);
    }
}
