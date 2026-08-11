<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Unit;
use Illuminate\Http\Request;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = Karyawan::with('unit')->latest()->paginate(10);
        $units = Unit::all();
        return view('karyawan.index', compact('karyawans','units'));
    }

   public function store(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'nik' => 'required|unique:karyawans,nik',
            'nama' => 'required|string|max:225',
        ]);

        Karyawan::create($request->all());
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id,',
            'nik' => 'required|unique:karyawans,nik,' . $karyawan->id,
            'nama' => 'required|string|max:255',
        ]);

        $karyawan->update($request->all());
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil diupdate');
    }

    public function destroy(Karyawan $karyawan)
    {
        $karyawan->delete();
        return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus');
    }
}
