<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\History;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class BahanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bahans = Bahan::all();
        
        $satuans = Satuan::all();
        $history = Bahan::orderBy('created_at', 'desc')->get();
        return view('stok.bahan', compact('bahans','satuans', 'history'));
    }

    public function add_bahan(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required',
            'satuan' => 'required|exists:satuan,satuan',
            'jml_stok' => 'required|numeric',
            // 'name' => 'required|exists:users,name'
        ]);

        // Cek apakah bahan sudah ada dalam database
        $existing_bahan = Bahan::where('nama_bahan', $request->nama_bahan)->first();

        if ($existing_bahan) {
            // Jika bahan sudah ada, tambahkan stoknya
            $existing_bahan->jml_stok += $request->jml_stok;
            $existing_bahan->save();
        } else {
            // Jika bahan belum ada, buat bahan baru
            Bahan::create([
                'nama_bahan' => $request->nama_bahan,
                'satuan' => $request->satuan,
                'jml_stok' => $request->jml_stok
            ]);
        }

        // Simpan setiap penambahan stok ke tabel history
        History::create([
            'nama_bahan' => $request->nama_bahan,
            'satuan' => $request->satuan,
            'jml_stok' => $request->jml_stok,
            'keterangan' => 'add stok',
            // 'user_id' => auth()->user()->id
        ]);

        return back()->with('success', 'Berhasil ditambahkan');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Bahan  $bahan
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        // $bahans = Bahan::with('satuan')->get();
        $bahans = Bahan::all();
        
        $satuans = Satuan::all();
        return view('stok.listbahan', compact('bahans','satuans'));
    }

    public function edit($id)
    {
        $bahan = Bahan::findOrFail($id);
        return view('stok.editbahan', compact('bahan'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'nama_bahan' => 'required',
        'jml_stok' => 'required|numeric',
        'satuan' => 'required',
    ]);

    $bahan = Bahan::findOrFail($id);
    $bahan->update($request->all());

    History::create([
        'nama_bahan' => $bahan->nama_bahan,
        'satuan' => $bahan->satuan,
        'jml_stok' => $bahan->jml_stok,
        'keterangan' => 'edit',
        // 'user_id' => auth()->user()->id
    ]);

    return redirect()->route('listmenu')->with('success', 'Bahan berhasil diperbarui.');
}

public function destroy($id)
{
    $bahan = Bahan::findOrFail($id);
    $bahan->delete();
    
    History::create([
        'nama_bahan' => $bahan->nama_bahan,
        'satuan' => $bahan->satuan,
        'jml_stok' => $bahan->jml_stok,
        'keterangan' => 'delete',
        // 'user_id' => auth()->user()->id
    ]);
    
    return redirect()->route('showbahan')->with('success', 'Bahan berhasil dihapus.');
}


     public function history(Request $request)
     {
         $query = History::query();
 
         if ($request->has('start_date')) {
             $query->whereDate('created_at', '>=', $request->start_date);
         }
 
         if ($request->has('end_date')) {
             $query->whereDate('created_at', '<=', $request->end_date);
         }
 
         $history = $query->orderBy('created_at', 'desc')
                          ->select('nama_bahan', 'satuan', 'jml_stok', 'created_at','keterangan')
                          ->get();
 
         return view('stok.history', compact('history'));
     }
}
