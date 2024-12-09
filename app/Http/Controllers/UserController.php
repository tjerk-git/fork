<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'name' => 'required',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        return redirect()->route('users.index')->with('success', 'Gebruiker succesvol aangemaakt');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->email = $validated['email'];

        $user->save();

        return redirect()->route('users.index')->with('success', 'Gebruiker succesvol bijgewerkt');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Gebruiker succesvol verwijderd');
    }
}
