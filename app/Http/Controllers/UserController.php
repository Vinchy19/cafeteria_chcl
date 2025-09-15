<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('front.users.index', compact('users'));
    }

    public function create()
    {
        return view('front.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|confirmed|min:8',
            'role' => 'required|in:admin,user',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('front.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('front.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,user',
            'password' => 'nullable|string|min:8',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur modifié avec succès.');
    }


    public function destroy($id)
    {
        // Empêcher l'utilisateur de se supprimer lui-même
        if ($id == Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }

    // Méthode pour modifier le mot de passe
//    public function editPassword($id)
//    {
//        $user = User::findOrFail($id);
//        return view('front.users.edit-password', compact('user'));
//    }
//
//    public function updatePassword(Request $request, $id)
//    {
//        $user = User::findOrFail($id);
//
//        $validated = $request->validate([
//            'password' => 'required|confirmed|min:8',
//        ]);
//
//        $user->update([
//            'password' => Hash::make($validated['password']),
//        ]);
//
//        return redirect()->route('users.index')
//            ->with('success', 'Mot de passe modifié avec succès.');
//    }
}
