<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\BahanInisiasi;

use App\Models\Unit;
use App\Models\History;
use Illuminate\Http\Request;
use App\Models\Historymenuterjual;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Notification; 
use App\Models\Outlet;

use Illuminate\Support\Facades\Auth;

class BahanController extends Controller
{

    public function index(Request $request)
    {

    $outlet = Outlet::all();
    $units = Unit::all();
    $bahan_inisiasi = BahanInisiasi::all();

    $outlet_id = $request->input('outlet_id');
    $bahans = Bahan::where('outlet_id', $outlet_id)->get();

        // Hitung instock dan satuan untuk setiap bahan
        foreach ($bahans as $bahan) {
            $bahan->instock = $this->get_konversi($bahan->id, $outlet_id);
            $bahan->satuan = $this->get_satuan($bahan->bahan_inisiasi->id); // Ambil satuan dari BahanInisiasi
        }


    $selectedOutlet = $request->query('outlet_id', $outlet->first()->id ?? null);

    return view('stok.bahan', compact('bahans','units', 'outlet', 'selectedOutlet', 'outlet_id', 'bahan_inisiasi'));
    }
// daftar bahan
    public function show(Request $request)
    {
        $outlet = Outlet::all();
        $units = Unit::all();
        $outlet_id = $request->input('outlet_id');
        $bahans = Bahan::where('outlet_id', $outlet_id)->get();
    
        // Hitung instock dan satuan untuk setiap bahan
        foreach ($bahans as $bahan) {
            $bahan->instock = $this->get_konversi($bahan->id, $outlet_id);
            $bahan->satuan = $this->get_satuan($bahan->bahan_inisiasi->id); // Ambil satuan dari BahanInisiasi
        }
    
        $selectedOutlet = $request->query('outlet_id', $outlet->first()->id ?? null);
    
        return view('stok.daftarBahan', compact('bahans', 'units', 'outlet', 'selectedOutlet', 'outlet_id'));
    }
    
    public function add_bahan(Request $request)
    {
        $request->validate([

            'bahan_id' => 'required|exists:bahan_inisiasi,id',
            'qty_stok' => 'required|numeric',
            'unit_id' => 'required|exists:unit,id',
            'outlet_id' => 'required|exists:outlets,id',

        ]);

        $existing_bahan = Bahan::where('bahan_id', $request->bahan_id)
            ->where('outlet_id', $request->outlet_id)
            ->first();

        if ($existing_bahan) {
            // Logika untuk barang sudah ada stok
            $existing_bahan->qty_stok += $request->qty_stok;
            $existing_bahan->save();
        } elseif (!$existing_bahan) {
            // Logika untuk barang baru
            $bahan = Bahan::create([
                'bahan_id' => $request->bahan_id,
                'qty_stok' => $request->qty_stok,
                'unit_id' => $request->unit_id,
                'outlet_id' => $request->outlet_id,
                'user_name' => Auth::user()->name
            ]);
        }

        History::create([
            'bahan_id' => $request->bahan_id,
            'qty_stok' => $request->qty_stok,
            'unit_id' => $request->unit_id,
            'outlet_id' => $request->outlet_id,
            'keterangan' => 'Add',
            'user_name' => Auth::user()->name
        ]);

        return redirect()->route('tambah.index', ['outlet_id' => $request->outlet_id])
        ->with('success', 'Bahan berhasil ditambahkan.');
}

    //     return back()->withInput(['outlet_id' => $request->input('outlet_id')])->with('success', 'Bahan berhasil ditambahkan.');

    // }

    public function edit(Request $request, $id)
    {
        $outlet_id = $request->input('outlet_id');
        $bahan = Bahan::findOrFail($id);
        $outlet = Outlet::all();
        $units = Unit::all();
        // $bahan_inisiasi = BahanInisiasi::where('outlet_id',$request->outlet_id)->get();
        $selectedOutlet = $request->query('outlet_id', $outlet->first()->id ?? null);

        return view('stok.editbahan', compact('bahan', 'outlet', 'units', 'selectedOutlet', 'outlet_id'),);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bahan_id' => 'required|exists:bahan,bahan_id',
            'qty_stok' => 'required|numeric',
            'unit_id' => 'required|exists:unit,id',
            'outlet_id' => 'required|exists:outlets,id',
        ]);

        // Fetch the Bahan record that needs to be updated
        $bahan = Bahan::findOrFail($id);

        // Check if there's an existing Bahan with the same bahan_id, outlet_id, and unit_id
        $existingBahan = Bahan::where('bahan_id', $request->bahan_id)
            ->where('outlet_id', $request->outlet_id)
            ->where('unit_id', $request->unit_id)
            ->where('id', '!=', $id) // Exclude the current record
            ->first();

        if ($existingBahan) {
            // If an existing record is found, update its quantity
            $existingBahan->qty_stok += $request->qty_stok;
            $existingBahan->save();

            // Optionally, you might want to delete the current record if it becomes redundant
            // $bahan->delete();
        } else {
            // Otherwise, just update the current record
            $update=$bahan->update([
                'bahan_id' => $request->bahan_id,
                'qty_stok' => $request->qty_stok,
                'unit_id' => $request->unit_id,
                'outlet_id' => $request->outlet_id,
                'user_name' => Auth::user()->name

            ]);
            History::create([
                'bahan_id' => $request->bahan_id,
                'qty_stok' => $request->qty_stok,
                'unit_id' => $request->unit_id,
                'outlet_id' => $request->outlet_id,
                'keterangan' => 'Edit',
                'user_name' => Auth::user()->name
            ]);
        }


        return redirect()->route('tambah.index', ['outlet_id' => $request->input('outlet_id')])
            ->with('success', 'Bahan berhasil diperbarui');
            
    }


    public function history(Request $request)
    {
        $outlet_id = $request->input('outlet_id');
        $history = History::where('outlet_id', $outlet_id)->orderBy('created_at', 'desc')->get();
        $outlet = Outlet::all();
        $unit = Unit::all();
        $selectedOutlet = $request->query('outlet_id', $outlet->first()->id ?? null);


        return view('stok.history', compact('history', 'outlet', 'unit', 'selectedOutlet'));
    }

    public function bkurangview(Request $request)
    {
        $bahans = Bahan::where('outlet_id', $request->outlet_id)->get();
        $units = Unit::all();
        $outlet = Outlet::all();
        $selectedOutlet = $request->query('outlet_id', $outlet->first()->id ?? null);
        // dd($request);
        $history = Bahan::orderBy('created_at', 'desc')->get();
        return view('stok.bkurang', compact('bahans', 'units', 'history', 'outlet', 'selectedOutlet'));
    }

    public function bkurang(Request $request)
    {
        Log::info('Request data: ' . json_encode($request->all()));

        $request->validate([
            'bahan_id' => 'required|string|max:255|exists:bahan,bahan_id',
            'qty_stok' => 'required|numeric',
            'unit_id' => 'required|exists:unit,id',
            'outlet_id' => 'required|exists:outlets,id',
        ]);


        $bahan = Bahan::where('bahan_id', $request->bahan_id)
            ->where('outlet_id', $request->outlet_id)
            ->first();

        if ($bahan) {
            Log::info('Bahan found: ' . json_encode($bahan));
            $bahan->qty_stok -= $request->qty_stok;
            $bahan->save();
            History::create([
                'bahan_id' => $request->bahan_id,
                'qty_stok' => $request->qty_stok,
                'unit_id' => $request ->unit_id,
                'outlet_id' => $request->outlet_id,
                'keterangan' => 'Dikurangi',
                'user_name' => Auth::user()->name
            ]);
        }


        return redirect()->route('kurangview',['outlet_id' => $request->outlet_id])->with('success', 'Bahan Berhasil dikurangi.');

    }


    public function getUnit($bahanId, $outletId)
    {
        $bahan = BahanInisiasi::with('unit')
            ->where('id', $bahanId)
            ->where('outlet_id', $outletId)
            ->first();

        if ($bahan && $bahan->unit) {
            return response()->json([
                'unit_id' => $bahan->unit->id,
                'unit_name' => $bahan->unit->unit,
            ]);
        } else {
            return response()->json([
                'unit_id' => '',
                'unit_name' => '',
            ], 404);
        }
    }

    public function getBahans($outletId)
    {
        $bahans = Bahan::with('bahan_inisiasi')->where('outlet_id', $outletId)->get(['id', 'nama_bahan']);
        return response()->json($bahans);
    }

    public function getUnitAddbahan($bahanId, $outletId)
    {
        // Find the Bahan by id and outlet_id
        $bahan = BahanInisiasi::with('unit')
            ->where('id', $bahanId)
            ->where('outlet_id', $outletId)
            ->first();

        // Check if the Bahan exists and has a related Unit
        if ($bahan && $bahan->unit) {
            return response()->json([
                'unit_id' => $bahan->unit->id,
                'unit_name' => $bahan->unit->unit,
            ]);
        } else {
            return response()->json([
                'unit_id' => '',
                'unit_name' => '',
            ], 404);
        }
    }

    public function getBahansAddbahan($outletId)
    {
            $bahans = BahanInisiasi::where('outlet_id', $outletId)->get(['id', 'nama_bahan']);
            return response()->json($bahans);
    }

    public function get_konversi($bahan_id, $outlet_id)
    {
        $existing_bahan = Bahan::where('bahan_id', $bahan_id)
                            ->where('outlet_id', $outlet_id)
                            ->first();
        
        
        $existing_bahan_inisiasi = BahanInisiasi::where('id', $bahan_id)
                            ->where('outlet_id', $outlet_id)
                            ->first();
        
        if ($existing_bahan && $existing_bahan_inisiasi) {
            $konversi = $existing_bahan->qty_stok / $existing_bahan_inisiasi->qty_inisiasi;
            // $konversi -= 1;
            return ceil($konversi); // Mengembalikan nilai konversi
        }
        
        return null; // Mengembalikan null jika data tidak ditemukan
    }

    public function get_satuan($bahan_id)
    {
        // Cari satuan dari tabel BahanInisiasi berdasarkan bahan_id
        $existing_bahan_inisiasi = BahanInisiasi::where('id', $bahan_id)->first();
    
        if ($existing_bahan_inisiasi && $existing_bahan_inisiasi->satuan) {
            return $existing_bahan_inisiasi->satuan->satuan; // Return satuan field
        }
    
        return 'N/A'; // Jika tidak ditemukan, return 'N/A' atau bisa dikosongkan
    }

    
    
}
