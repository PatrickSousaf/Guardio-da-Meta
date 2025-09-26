<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsTeacher
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isTeacher()) {
            return $next($request);
        }

        return redirect('/home')->with('error', 'Acesso não autorizado.');
    }
}
