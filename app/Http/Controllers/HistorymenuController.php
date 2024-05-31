<?php

namespace App\Http\Controllers;

use App\Models\Historymenu;
use Illuminate\Http\Request;

class HistorymenuController extends Controller
{
    public function index()
    {
        $histories = Historymenu::all();
        
        return view('menu.historymenu', compact('histories'));
    }

    // Tambahkan fungsi-fungsi lainnya sesuai kebutuhan
}