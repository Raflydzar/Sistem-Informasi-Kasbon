<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::latest()->paginate(10);
        return view('unit.index', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_unit' => 'required|unique:units',
            'nama_unit' => 'required',
        ]);

        Unit::create($request->all());
        return redirect()->route('unit.index')->with('success', 'Unit berhasil ditambahkan');
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'kode_unit' => 'required|unique:units,kode_unit,' . $unit->id,
            'nama_unit' => 'required',
        ]);

        $unit->update($request->all());
        return redirect()->route('unit.index')->with('success', 'Unit berhasil diupdate');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('unit.index')->with('success', 'Unit berhasil dihapus');
    }
}
