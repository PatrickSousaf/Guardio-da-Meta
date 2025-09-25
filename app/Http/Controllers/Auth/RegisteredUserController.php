<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view in dashboard.
     */
    public function create(): View|RedirectResponse
    {
        // Verificação de role - apenas diretores podem acessar
        if (Auth::user()->role !== 'director') {
            return redirect()->route('dashboard')->with('error', 'Acesso restrito a diretores.');
        }

        return view('auth.register-simple');
    }

    /**
     * Handle an incoming registration request from director.
     */
    public function store(Request $request): RedirectResponse
    {
        // Verificação de role - apenas diretores podem registrar
        if (Auth::user()->role !== 'director') {
            return redirect()->route('dashboard')->with('error', 'Acesso restrito a diretores.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:director,management,teacher'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        event(new Registered($user));

        return redirect()->route('register')->with('success', 'Usuário cadastrado com sucesso!');
    }
}
