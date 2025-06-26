<?php

namespace App\Http\Controllers;

use App\Models\Photobooth;
use App\Models\User;
use Illuminate\Http\Request;

class PhotoboothController extends Controller
{
    public function index()
    {
        $photobooths = Photobooth::with('user')->get();
        $users = User::all();

        return view('photobooths.index', compact('photobooths', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:50',
            'alamat' => 'required',
            'user_id' => 'required|exists:users,id'
        ]);

        Photobooth::create($request->only('nama', 'alamat', 'user_id'));

        return redirect()->back()->with('success', 'Photobooth berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|max:50',
            'alamat' => 'required',
            'user_id' => 'required|exists:users,id'
        ]);

        $photobooth = Photobooth::findOrFail($id);
        $photobooth->update($request->only('nama', 'alamat', 'user_id'));

        return redirect()->back()->with('success', 'Photobooth berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $photobooth = Photobooth::findOrFail($id);
        $photobooth->delete();

        return redirect()->back()->with('success', 'Photobooth berhasil dihapus.');
    }
}
