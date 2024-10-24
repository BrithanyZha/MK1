<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function index(Request $request)
    {
        $outlet = Outlet::all();
        $selectedOutlet = $request->query('outlet_id', $outlet->first()->id ?? null);
        return view('stok.outlet',compact('outlet','selectedOutlet'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'nama_outlet' => 'required|unique:outlets',
            'address' => 'required',
        ]);

        Outlet::create([
            'nama_outlet' => $request->nama_outlet,
            'address' => $request->address,
        ]);

        return back()->with('success', 'Berhasil ditambahkan');
    }

    public function show()
    {
        $outlet = Outlet::all();
        return view('stok.listoutlet', compact('outlet'));
    }

    public function edit($id)
    {
        $outlet = Outlet::findOrFail($id);
        return view('stok.editoutlet', compact('outlet'));
    }

    public function update(Request $request, $id)
    {
        $outlet = Outlet::findOrFail($id);

        $request->validate([
            'nama_outlet' => 'required|unique:outlets,nama_outlet,' . $outlet->id,
            'address' => 'required',
        ]);

        $outlet->update([
            'nama_outlet' => $request->nama_outlet,
            'address' => $request->address,
        ]);

        return redirect()->route('outlet')->with('success', 'Outlet updated successfully.');
    }

    public function destroy($id)
    {
        $outlet = Outlet::findOrFail($id);
        $outlet->delete();

        return redirect()->route('outlet')->with('success', 'Outlet deleted successfully.');
    }
}
