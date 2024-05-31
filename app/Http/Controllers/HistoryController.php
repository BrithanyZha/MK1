<?php

namespace App\Http\Controllers;

use App\Models\Addmenu;
use App\Models\Menuterjual;
use Illuminate\Http\Request;

class MenuterjualController extends Controller
{
    public function index()
    {
        $menu = Addmenu::all();
        return view('menu.menuterjual', compact('menu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|exists:addmenus,nama_menu',
            'jumlah' => 'required|integer',
        ]);

        Menuterjual::create([
            'nama_menu' => $request->nama_menu,
            'jumlah' => $request->jumlah,
        ]);

        return redirect()->route('menuterjual.index')->with('success', 'Menu baru berhasil ditambahkan');
    }
}
