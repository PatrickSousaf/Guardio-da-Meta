<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\InviteCode;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View|RedirectResponse
    {
        // Verificação manual no controller
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Faça login para acessar esta página.');
        }

        if (Auth::user()->role !== 'director') {
            return redirect()->route('dashboard')->with('error', 'Acesso restrito a diretores.');
        }

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        // Verificação manual
        if (!Auth::check() || Auth::user()->role !== 'director') {
            return redirect()->route('login')->with('error', 'Acesso restrito a diretores.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:director,management,teacher'],
            'invite_code' => ['required', 'string'],
        ]);

        $inviteCode = InviteCode::where('code', strtoupper($request->invite_code))->first();

        if (!$inviteCode || !$inviteCode->isValid()) {
            return back()->withErrors([
                'invite_code' => 'Código de convite inválido, expirado ou já utilizado.'
            ])->withInput();
        }

        if ($request->role !== $inviteCode->role) {
            return back()->withErrors([
                'role' => 'Função não permitida para este código de convite.'
            ])->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        $inviteCode->increment('uses');
        Session::forget(['invite_validated', 'invite_code', 'allowed_role']);

        event(new Registered($user));

        return redirect()->route('dashboard')->with('success', 'Usuário registrado com sucesso!');
    }

    /**
     * Validar código de convite
     */
    public function validateInvite(Request $request): RedirectResponse
    {
        if (!Auth::check() || Auth::user()->role !== 'director') {
            return redirect()->route('login')->with('error', 'Acesso restrito a diretores.');
        }

        $request->validate([
            'invite_code' => 'required|string|max:50']);

        $inviteCode = InviteCode::where('code', strtoupper($request->invite_code))->first();

        if (!$inviteCode || !$inviteCode->isValid()) {
            return back()->withErrors([
                'invite_code' => 'Código de convite inválido, expirado ou já utilizado.'
            ])->withInput();
        }

        Session::put('invite_validated', true);
        Session::put('invite_code', $inviteCode->code);
        Session::put('allowed_role', $inviteCode->role);

        return redirect()->route('register');
    }
}
