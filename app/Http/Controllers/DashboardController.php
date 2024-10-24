<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bahan;
use App\Models\Outlet;
use App\Models\Unit;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $outlet_id = $request->input('outlet_id');
        $selectedOutlet = $request->input('outlet_id');
        $outlet = Outlet::all();

        if ($selectedOutlet) {
            $bahans = Bahan::where('outlet_id', $selectedOutlet)->get();
            
        } else {
            $bahans = Bahan::all();
        }

        $units = Unit::all();

      

        return view('dashboard.index', compact('outlet', 'bahans', 'units', 'selectedOutlet','outlet_id'));
    }
}
