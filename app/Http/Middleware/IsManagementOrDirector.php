<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsManagementOrDirector
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && (auth()->user()->isManagement() || auth()->user()->isDirector())) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => 'Acesso não autorizado. Apenas gestores e diretores podem realizar esta ação.'], 403);
        }

        return redirect('/dashboard')->with('error', 'Acesso não autorizado. Apenas gestores e diretores podem realizar esta ação.');
    }
}
