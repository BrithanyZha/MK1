<?php

namespace App\Http\Controllers;

use App\Models\Addmenu;
use Illuminate\Http\Request;
use App\Models\Menuterjual;
use Illuminate\Support\Facades\Auth;

class MenuterjualController extends Controller
{
    //
    public function index()
{
    $menu = Addmenu::all();
    
    return view('menu.menuterjual', compact('menu'));
}

public function show()
{
    $menus = Menuterjual::all();
    
    return view('menu.historymenuterjual', compact('menus'));
}

public function create(Request $request)
{
    $request->validate([
        'nama_menu' => 'required|string|max:255|exists:Addmenus,nama_menu',
        'jumlah' => 'required|integer'

    ]);

    Menuterjual::create([
        'nama_menu' => $request->nama_menu,
        'jumlah' => $request->jumlah,
        'user_name' => Auth::user()->name
    ]);

    return redirect()->route('menuterjual');
    
}
}
