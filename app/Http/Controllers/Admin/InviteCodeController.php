<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InviteCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InviteCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'director') {
                abort(403, 'Acesso restrito a diretores.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $inviteCodes = InviteCode::orderBy('created_at', 'desc')->get();
        return view('admin.invite-codes.index', compact('inviteCodes'));
    }

    public function create()
    {
        return view('admin.invite-codes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required|in:director,management,teacher',
            'max_uses' => 'required|integer|min:1|max:1000',
            'expires_in_days' => 'required|integer|min:1|max:365',
        ]);

        // Gerar código único
        do {
            $code = strtoupper(Str::random(3)) . date('Y') . strtoupper(Str::random(3));
        } while (InviteCode::where('code', $code)->exists());

        InviteCode::create([
            'code' => $code,
            'role' => $request->role,
            'max_uses' => $request->max_uses,
            'expires_at' => now()->addDays($request->expires_in_days),
        ]);

        return redirect()->route('admin.invite-codes.index')
            ->with('success', 'Código de convite criado: ' . $code);
    }

    public function destroy(InviteCode $inviteCode)
    {
        $inviteCode->delete();
        return redirect()->route('admin.invite-codes.index')
            ->with('success', 'Código deletado com sucesso.');
    }

    public function deactivate(InviteCode $inviteCode)
    {
        $inviteCode->update(['is_active' => false]);
        return redirect()->back()->with('success', 'Código desativado.');
    }

    public function activate(InviteCode $inviteCode)
    {
        $inviteCode->update(['is_active' => true]);
        return redirect()->back()->with('success', 'Código ativado.');
    }
}
