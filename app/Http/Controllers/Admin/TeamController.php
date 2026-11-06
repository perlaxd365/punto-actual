<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user()->isAdmin(), 403);

        return view('admin.team.index', [
            'users' => User::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        abort_unless($request->user()->isAdmin(), 403);

        $data = $request->validate([
            'role' => 'required|in:admin,editor',
        ]);

        if ($user->id === $request->user()->id && $data['role'] !== 'admin') {
            return back()->withErrors(['role' => 'No puedes quitarte el rol de administrador.']);
        }

        $user->update(['role' => $data['role']]);

        return back()->with('status', 'Rol actualizado para '.$user->name.'.');
    }
}
