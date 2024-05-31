<?php
namespace App\Http\Controllers;

use App\Models\Perhitunganstok;
use Illuminate\Http\Request;

class PerhitunganstokController extends Controller
{
    public function index()
    {
        $perhitunganstoks = Perhitunganstok::all();
        return view('menu.perhitunganstok', compact('perhitunganstoks'));
    }
}
