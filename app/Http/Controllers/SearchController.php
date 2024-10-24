<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\History;
use App\Models\Historymenu;
use App\Models\Historysebelum;
use App\Models\Menuterjual;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function searchbahan(){
        
    }





    // public function search(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $query = $request->input('query');

    //         $bahanResults = Bahan::where('name', 'LIKE', "%{$query}%")->get();
    //         $menuterjualResults = Menuterjual::where('name', 'LIKE9', "%{$query}%")->get();
    //         $historyResults = History::where('name', 'LIKE', "%{$query}%")->get();
    //         $historymenuResults = Historymenu::where('name', 'LIKE', "%{$query}%")->get();
    //         $historysebelumResults = Historysebelum::where('name', 'LIKE', "%{$query}%")->get();

    //         $results = $bahanResults->merge($menuterjualResults)
    //                                 ->merge($historyResults)
    //                                 ->merge($historymenuResults)
    //                                 ->merge($historysebelumResults);

    //         return response()->json(['results' => $results]);
    //     }
        
    //     return response()->json(['message' => 'Bad Request'], 400);
    // }
}
