<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsManagement
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isManagement()) {
            return $next($request);
        }

        return redirect('/home')->with('error', 'Acesso não autorizado.');
    }
}
