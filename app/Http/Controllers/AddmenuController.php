<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\Satuan;
use App\Models\Addmenu;
use App\Models\Historymenu;
use Illuminate\Http\Request;
use App\Models\Perhitunganstok;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AddmenuController extends Controller
{
    public function index()
    {
        $addmenus = Addmenu::all();
        $bahans = Bahan::all();
        $satuans = Satuan::all();
        return view('menu.addmenu', compact('bahans', 'satuans', 'addmenus'));
    }

    public function listmenu()
    {
        $menus = Addmenu::all();
        return view('menu.listmenu', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|unique:addmenus',
            'nama_bahan' => 'required|array|exists:bahan,nama_bahan',
            'jml_takaran' => 'required|array',
            'satuan' => 'required|array|exists:satuan,satuan',
            'nama_bahan.*' => 'required|string|exists:bahan,nama_bahan',
            'jml_takaran.*' => 'required|integer',
            'satuan.*' => 'required|string|exists:satuan,satuan',
        ]);

        $addmenu = Addmenu::create([
            'nama_menu' => $request->nama_menu,
            'nama_bahan' => $request->nama_bahan,
            'jml_takaran' => $request->jml_takaran,
            'satuan' => $request->satuan,
        ]);

        foreach ($request->nama_bahan as $index => $nama_bahan) {
            $bahan = Bahan::where('nama_bahan', $nama_bahan)->first();
            $sisa = $bahan->jumlah - $request->jml_takaran[$index];

            Perhitunganstok::create([
                'nama_barang' => $nama_bahan,
                'jml_sisa' => $sisa,
                'satuan' => $request->satuan[$index],
            ]);
        }

        Historymenu::create([
            'nama_menu' => $addmenu->nama_menu,
            'nama_bahan' => json_encode($addmenu->nama_bahan),
            'jml_takaran' => json_encode($addmenu->jml_takaran),
            'satuan' => json_encode($addmenu->satuan),
            'keterangan' => 'add',
            'user_name' => Auth::user()->name
        ]);

        return redirect()->route('addmenu.index')->with('success', 'Menu added successfully.');
    }

    public function edit(Addmenu $addmenu)
    {
        $bahans = Bahan::all();
        $satuans = Satuan::all();
        return view('menu.editmenu', compact('addmenu', 'bahans', 'satuans'));
    }

    public function update(Request $request, Addmenu $addmenu)
    {
        $request->validate([
            'nama_menu' => 'required|unique:addmenus,nama_menu,' . $addmenu->id,
            'nama_bahan' => 'required|array|exists:bahan,nama_bahan',
            'jml_takaran' => 'required|array',
            'satuan' => 'required|array|exists:satuan,satuan',
            'nama_bahan.*' => 'required|string|exists:bahan,nama_bahan',
            'jml_takaran.*' => 'required|integer',
            'satuan.*' => 'required|string|exists:satuan,satuan',
        ]);

        $addmenu->update([
            'nama_menu' => $request->nama_menu,
            'nama_bahan' => $request->nama_bahan,
            'jml_takaran' => $request->jml_takaran,
            'satuan' => $request->satuan,
        ]);

        Historymenu::create([
            'nama_menu' => $addmenu->nama_menu,
            'nama_bahan' => json_encode($addmenu->nama_bahan),
            'jml_takaran' => json_encode($addmenu->jml_takaran),
            'satuan' => json_encode($addmenu->satuan),
            'keterangan' => 'update',
            'user_name' => Auth::user()->name
        ]);

        return redirect()->route('listmenu')->with('success', 'Menu updated successfully.');
    }

    public function destroy(Addmenu $addmenu)
    {
        Historymenu::create([
            'nama_menu' => $addmenu->nama_menu,
            'nama_bahan' => json_encode($addmenu->nama_bahan),
            'jml_takaran' => json_encode($addmenu->jml_takaran),
            'satuan' => json_encode($addmenu->satuan),
            'keterangan' => 'delete',
            'user_name' => Auth::user()->name
        ]);

        $addmenu->delete();

        return redirect()->route('listmenu')->with('success', 'Menu deleted successfully.');
    }
}
