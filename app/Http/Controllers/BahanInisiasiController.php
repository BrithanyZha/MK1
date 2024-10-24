<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Unit;
use App\Models\Bahan;
use App\Models\Outlet;
use App\Models\Addmenu;
use App\Models\BahanInisiasi;
use App\Models\Historymenu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

class BahanInisiasiController extends Controller
{
    public function index(Request $request)
    {
        $outlet_id = $request->input('outlet_id');
        $bahanInisiasi = BahanInisiasi::where('outlet_id', $outlet_id)->get();
        $units = Unit::all();
        $outlet = Outlet::all();
        $selectedOutlet = $request->query('outlet_id', $outlet->first()->id ?? null);

        return view('stok.bahanInisiasi', compact('units', 'outlet', 'bahanInisiasi', 'outlet_id', 'selectedOutlet'));
    }
    public function show()
    {
        $bahanInisiasi = BahanInisiasi::all();
        $units = Unit::all();
        $outlet = Outlet::all();

        return view('stok.bahanInisiasi', compact('units', 'outlet', 'bahanInisiasi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'nama_bahan' => 'required|string|max:255',
            'qty_inisiasi' => 'required|numeric',
            'unit_id' => 'required|exists:unit,id',
            'qty_stok' => 'required|numeric',
        ]);



        $bahanInisiasi = BahanInisiasi::create([
            'outlet_id' => $request->outlet_id,
            'nama_bahan' => $request->nama_bahan,
            'qty_inisiasi' => $request->qty_inisiasi,
            'unit_id' => $request->unit_id,
            // 'perbungkus' => $request->perbungkus
        ]);
        $bahan = Bahan::create([
            'bahan_id' => $bahanInisiasi->id,
            'qty_stok' => $request->qty_stok,
            'unit_id' => $bahanInisiasi->unit_id,
            'outlet_id' => $bahanInisiasi->outlet_id,
            'user_name' => Auth::user()->name
        ]);

        // history bahan ke add belum

        return redirect()->route('bahan_inisiasi', ['outlet_id' => $request->outlet_id])->with('success', 'Successfully added');
    }


    public function edit(Request $request, $id)
    {
        $bahanInisiasi = BahanInisiasi::findOrFail($id);
        $outlet = Outlet::all();
        $units = Unit::all();

        $selectedOutlet = $request->query('outlet_id', $outlet->first()->id ?? null);

        return view('stok.editBahanInisiasi', compact('outlet', 'units', 'bahanInisiasi', 'selectedOutlet'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'nama_bahan' => 'required|string|max:255',
            'qty_inisiasi' => 'required|numeric',
            'unit_id' => 'required|exists:unit,id',
        ]);

        $bahanInisiasi = BahanInisiasi::findOrFail($id);
        $bahanInisiasi->update([
            'nama_bahan' => $request->input('nama_bahan'),
            'qty_inisiasi' => $request->input('qty_inisiasi'),
            'unit_id' => $request->input('unit_id'),
            'outlet_id' => $request->input('outlet_id'),
        ]);

        return redirect()->route('bahan_inisiasi', ['outlet_id' => $request->input('outlet_id')])
            ->with('success', 'Successfully updated.');
    }

    // public function destroy($id)
    // {
    //     $bahanInisiasi = BahanInisiasi::findOrFail($id);
    //     $bahanInisiasi->delete();

    //     return back()->with('success', 'Bahan inisiasi berhasil dihapus.');
    // }

}
