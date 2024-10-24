<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\AddMenu;
use App\Models\Bahan;
use App\Models\Unit;
use App\Models\Outlet;

class MenuController extends Controller
{
    public function index(Request $request)
    {
   
        $outlet = Outlet::all();
    
        $selectedOutlet = $request->query('outlet_id', $outlet->first()->id ?? null);
    
        $outlet_id = $request->input('outlet_id');
        $menu = Menu::with('outlet')->get();
        $outlet = Outlet::all();
        return view('menu.namamenu', compact('menu', 'outlet', 'selectedOutlet', 'outlet_id',));
    }

    public function delete($menuId)
    {
        $menu = Menu::findOrFail($menuId);
        Addmenu::where('menu_id', $menu->id)->delete();
        $menu->delete();

        return response()->json(['success' => true]);
    }
}
