<?php

namespace App\Http\Controllers;

use App\Models\Kas;
use Illuminate\Http\Request;

class KasController extends Controller
{
    public function index()
    {
        $kas = Kas::latest()->paginate(10);
        return view('kas.index', compact('kas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kas' => 'required|string|max:225',
            'nik' => 'required|numeric|min:0',
        ]);

        Kas::create([
            'nama_kas' => $request->nama_kas,
            'saldo_awal' => $request->saldo_awal,
            'saldo_sekarang' => $request->saldo_awal,
        ]);
        return redirect()->route('kas.index')->with('success', 'Kas berhasil dibuat');
    }

    public function update(Request $request, Kas $kas)
    {
        $request->validate([
            'nama_kas' => 'required|string|max:255',
        ]);

        $kas->update(['nama_kas' => $request->nama_kas]);
        return redirect()->route('karyawan.index')->with('success', 'Data kas berhasil diupdate');
    }

    public function destroy(Kas $kas)
    {
        $kas->delete();
        return redirect()->route('kas.index')->with('success', 'Kas berhasil dihapus');
    }
}
