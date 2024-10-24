<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use Illuminate\Http\Request;
use App\Models\SoldMenuHistory;
use App\Http\Controllers\Controller;

class SoldMenuHistoryController extends Controller
{
    public function index(Request $request)
    {
        // Initialize the query builder
        $outlet = Outlet::all();
        $menut = SoldMenuHistory::with('outlet') // Ensure you are using the correct relationship
                         ->get();

        $selectedOutlet = $request->query('outlet_id', $outlet->first()->id ?? null);
        return view('menu.historymenuterjual', compact('menut', 'outlet', 'selectedOutlet'));
    }
}
