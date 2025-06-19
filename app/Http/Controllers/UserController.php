<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:Owner,Admin',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:Owner,Admin',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user = User::findOrFail($id);
        $user->update($data);

        return redirect()->back()->with('success', 'User berhasil diperbarui');
    }

    public function destroy($id)
    {
        // Cegah penghapusan user sendiri
        if (auth()->id() == $id) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus');
    }
}
