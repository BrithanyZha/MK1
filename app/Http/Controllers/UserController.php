<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\User;
use App\Models\Outlet;
use App\Models\History;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua outlet
        $outlet = Outlet::all();
        $units =Unit::all();
        $history=History::all();

        // Cek apakah ada data outlet
        if ($outlet->isNotEmpty() && $units->isNotEmpty()) {
            // Ambil outlet_id dari outlet pertama
            $outlet_id = $outlet->first()->id;
            $selectedOutletId = $request->query('outlet_id', $outlet->first()->id ?? null);
            // Redirect ke URL dengan parameter outlet_id jika ada outlet
            return redirect()->route('history','selectedOutletId', ['outlet_id' => $outlet_id]);
        } else {

            // Jika tidak ada data outlet, kembalikan ke halaman dengan pesan atau tindakan lainnya
            return view('stok.history', compact('outlet','history'));
            // return back()->with('success', 'Ingredient has been successfully added.');
            // return redirect()->route('logout');
        }
    }

}
