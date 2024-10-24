<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\MenuHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Addmenu;

class MenuHistoryController extends Controller
{
    public function index(Request $request)
    {
        // Initialize the query builder
        $query = MenuHistory::query();

        // $hmenu = MenuHistory::with(['nama_bahan','outlet', 'unit'])->orderBy('created_at', 'desc')->get();
        $outlet_id = $request->input('outlet_id');
        // $hmenu  = MenuHistory::where('outlet_id', $outlet_id)->orderBy('created_at', 'desc')->get();
        $hmenu  = MenuHistory::where('outlet_id', $outlet_id)->orderBy('created_at', 'desc')->get();
        $menus = MenuHistory::where('outlet_id', $outlet_id)->get();
        $outlet = Outlet::all();
        $selectedOutlet = $request->query('outlet_id', $outlet->first()->id ?? null);

        // Return the view with the filtered data
        return view('menu.historymenu', compact('hmenu', 'menus', 'outlet_id', 'selectedOutlet'));
    }
}
