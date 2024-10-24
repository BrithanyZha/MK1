<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Unit;
use App\Models\Bahan;
use App\Models\Outlet;
use App\Models\Addmenu;
// use App\Models\MenuHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
class AddmenuController extends Controller
{
    public function index(Request $request)
    {
        $outlet_id = $request->input('outlet_id');
        $menus = Addmenu::where('outlet_id', $outlet_id)->get();
        $bahans = Bahan::all();
        $units = Unit::all();
        $outlet = Outlet::all();
        $selectedOutlet = $request->query('outlet_id', $outlet->first()->id ?? null);

        return view('menu.addmenu', compact('bahans', 'units', 'outlet', 'menus', 'selectedOutlet', 'outlet_id'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'nama_menu' => 'required|string|max:255',
            'bahan_id' => 'required|array',
            'bahan_id.*' => 'required|exists:bahan,id',
            'qty_takaran' => 'required|array',
            'qty_takaran.*' => 'required',
            'unit_id' => 'required|array',
            'unit_id.*' => 'required|exists:unit,id',
        ]);

        // Create or find the menu by name
        $menu = Menu::firstOrCreate([
            'nama_menu' => $request->input('nama_menu'),
            'outlet_id' => $request->input('outlet_id'),
        ]);

        // Store each bahan (ingredient) for the menu
        foreach ($request->bahan_id as $key => $bahan_id) {
            Addmenu::create([
                'outlet_id' => $request->outlet_id,
                'menu_id' => $menu->id,
                'bahan_id' => $bahan_id,
                'qty_takaran' => $request->qty_takaran[$key],
                'unit_id' => $request->unit_id[$key],
            ]);
        }

        return redirect()->back()->with('success', 'Menu added successfully');
    }

    public function edit(Request $request, $menuId)
    {
        $menu = Menu::with('outlet')->findOrFail($menuId);
        $details = Addmenu::where('menu_id', $menuId)->with('bahan', 'unit')->get();
        $bahans = Bahan::all();
        $units = Unit::all();
        $outlet_id = $request->input('outlet_id');

        return view('menu.editresep', compact('menu', 'details', 'bahans', 'units', 'outlet_id', 'menuId'));
    }

    public function update(Request $request, $menuId)
{
    $request->validate([
        'outlet_id' => 'required|exists:outlets,id',
        'nama_menu' => 'required|string|max:255',
        'bahan_id' => 'required|array',
        'bahan_id.*' => 'required|exists:bahan,id',
        'qty_takaran' => 'required|array',
        'qty_takaran.*' => 'required',
        'unit_id' => 'required|array',
        'unit_id.*' => 'required|exists:unit,id',
    ]);

    // Update the menu name
    $menu = Menu::findOrFail($menuId);
    $menu->update([
        'nama_menu' => $request->input('nama_menu'),
        'outlet_id' => $request->input('outlet_id'),
    ]);

    // Delete previous details
    Addmenu::where('menu_id', $menuId)->delete();

    // Add the updated ingredients (bahan)
    foreach ($request->bahan_id as $key => $bahan_id) {
        Addmenu::create([
            'outlet_id' => $request->outlet_id,
            'menu_id' => $menu->id,
            'bahan_id' => $bahan_id,
            'qty_takaran' => $request->qty_takaran[$key],
            'unit_id' => $request->unit_id[$key],
        ]);
    }

    return redirect()->route('addmenu.index', ['outlet_id' => $request->outlet_id])
        ->with('success', 'Menu updated successfully');
}

    public function destroy(Request $request, $menuId)
    {
        Addmenu::where('menu_id', $menuId)->delete();
        Menu::where('id', $menuId)->delete();

        return redirect()->route('addmenu.index', ['outlet_id' => $request->outlet_id])
            ->with('success', 'Menu updated successfully');
    }

    public function getBahansmenu($outletId)
    {
        $bahans = Bahan::with('bahan_inisiasi')
                       ->where('outlet_id', $outletId)
                       ->get();
    
        return response()->json($bahans);
    }

    public function getMenus($outletId)
    {
        $menus = Menu::where('outlet_id', $outletId)->get();
        return response()->json($menus);
    }

    public function getUnitmenu($bahan_id)
    {
        // Find the Bahan by id
        $bahan = Bahan::with('unit')->where('bahan_id', $bahan_id)->first();

        // Check if the Bahan exists and has a related Unit
        if ($bahan && $bahan->unit) {
            return response()->json(['unit_id' => $bahan->unit->id, 'unit_name' => $bahan->unit->unit]);
        } else {
            return response()->json(['unit_id' => 'tidak ditemukan', 'unit_name' => ''], 404);
        }
    }

    public function getDetails($menuId)
    {
        $menu = Addmenu::with(['menu', 'outlet', 'details.bahan', 'details.unit'])
            ->findOrFail($menuId);

        return response()->json($menu);
    }
}
