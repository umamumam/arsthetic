<?php

namespace App\Http\Controllers;

use App\Models\Photobooth;
use Illuminate\Http\Request;

class PhotoboothController extends Controller
{
    public function index()
    {
        $photobooths = Photobooth::all();
        return view('photobooths.index', compact('photobooths'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'alamat' => 'required',
        ]);

        Photobooth::create($request->only('nama', 'alamat'));

        return redirect()->back()->with('success', 'Photobooth berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'alamat' => 'required',
        ]);

        $photobooth = Photobooth::findOrFail($id);
        $photobooth->update($request->only('nama', 'alamat'));

        return redirect()->back()->with('success', 'Photobooth berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $photobooth = Photobooth::findOrFail($id);
        $photobooth->delete();

        return redirect()->back()->with('success', 'Photobooth berhasil dihapus.');
    }
}
