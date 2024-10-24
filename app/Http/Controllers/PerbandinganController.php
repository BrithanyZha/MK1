<?php

namespace App\Http\Controllers;

use App\Models\StokAwalHistory;
use App\Models\Bahan;
use Illuminate\Http\Request;

class PerbandinganController extends Controller
{
    public function index(Request $request)
    {
        // Initialize the query builders
        $historysebelumQuery = StokAwalHistory::query();
        $bahanQuery = Bahan::query();

        // $historysebelum = $historysebelumQuery->orderBy('created_at', 'desc')->get();

        $bahans = $bahanQuery->orderBy('created_at', 'desc')->get();

        $historysebelum = StokAwalHistory::with(['outlet', 'unit'])->orderBy('created_at', 'desc')->get();

        // $bahans = Bahan::with('outlet')->get();


        // Return the view with the filtered data
        return view('Perbandingan.perbandingan', compact('historysebelum', 'bahans'));
    }
}

