<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\BahanInisiasi;
use App\Models\Outlet;
use App\Models\Unit;
use App\Models\History;
use Illuminate\Http\Request;
use App\Models\Transferbahan;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransferbahanController extends Controller
{

    public function show()
    {
        $transferbahan = Transferbahan::all();
        return view('stok.listtransferbahan', compact('transferbahan'));
    }
    public function index(Request $request)
    {
        $outlet_id = $request->input('outlet_id');
        $transferbahan = Transferbahan::all();
        $outlet = Outlet::all();
        $units = Unit::all();

        $bahans = Bahan::where('outlet_id', $outlet_id)->get();
        $selectedOutlet = $request->query('outlet_id', $outlet->first()->id ?? null);
        return view('stok.transferbahan', compact('transferbahan', 'outlet', 'bahans', 'units', 'selectedOutlet', 'outlet_id'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'tfto' => 'required|exists:outlets,id',
            'note' => 'nullable|string|max:255',
            'bahan_id' => 'required|array',
            'bahan_id.*' => 'required|exists:bahan,id',
            'qty_stok' => 'required|array',
            'qty_stok.*' => 'required|numeric|min:0.01',
            'unit_id' => 'required|array',
            'unit_id.*' => 'required|exists:unit,id',

        ]);
    
        DB::transaction(function () use ($request) {
            $outlet_id = $request->input('outlet_id');
            $tfto = $request->input('tfto');
            $note = $request->input('note');
            $bahan_id = $request->input('bahan_id');
            $qty_stok = $request->input('qty_stok');
            $unit_id = $request->input('unit_id');
    
            for ($i = 0; $i < count($bahan_id); $i++) {
                $bahanFrom = Bahan::where('bahan_id', $bahan_id[$i])
                    ->where('outlet_id', $outlet_id)
                    ->first();
                
                if ($bahanFrom) {
                    if ($bahanFrom->qty_stok < $qty_stok[$i]) {
                        // throw new \Exception('Stok tidak mencukupi untuk bahan ' . $bahan_id[$i]);
                        return back()->withErrors(['msg' => 'Insufficient stock of ingredient for transfer.' . $bahanFrom->nama_bahan]);

                    } else {
                        // 
                        $namabahan= BahanInisiasi::where('id', $bahan_id[$i])
                        ->where('outlet_id', $outlet_id)
                        ->first();
                    
                        $bahannamato = $namabahan->nama_bahan;
    

    
                        // Check if the same bahan name exists in the destination outlet
                        $noninisiasi = BahanInisiasi::where('nama_bahan', $bahannamato)
                            ->where('outlet_id', $tfto)
                            ->first();
    
                        if (!$noninisiasi) {
                            // If not exists, create new bahan in destination outlet
                            $bahanInisiasiTo = BahanInisiasi::create([
                                'outlet_id' => $tfto,
                                'nama_bahan' => $bahannamato,
                                'qty_inisiasi' => $namabahan->qty_inisiasi,
                                'unit_id' => $unit_id[$i],
                                'satuan_id' => $namabahan->satuan_id,
                                'minimal_stok' => $namabahan->minimal_stok


                            ]);
    
                            // Create new bahan entry in the destination outlet
                            $bahanTo = Bahan::create([
                                'bahan_id' => $bahanInisiasiTo->id,
                                'outlet_id' => $tfto,
                                'qty_stok' => $qty_stok[$i],
                                'unit_id' => $unit_id[$i],
                                'user_name' => Auth::user()->name,
                            ]);

                            $bahanFrom->qty_stok -= $qty_stok[$i];
                            $bahanFrom->save();
                        } else {
                            // If exists, update the existing bahan in destination outlet
                            $bahanFrom->qty_stok -= $qty_stok[$i];
    
                            $bahanTo = Bahan::where('bahan_id', $noninisiasi->id)
                                ->where('outlet_id', $tfto)
                                ->first();
    
                            $bahanTo->qty_stok += $qty_stok[$i];
                            $bahanFrom->save();
                            $bahanTo->save();
                        }
    
                        // Create the transfer record
                        Transferbahan::create([
                            'outlet_id' => $outlet_id,
                            'tfto' => $tfto,
                            'note' => $note,
                            'bahan_id' => $bahanTo->id,
                            'qty_stok' => $qty_stok[$i],
                            'unit_id' => $unit_id[$i],
                        ]);
    
                        // Create history records
                        History::create([
                            'bahan_id' => $bahanFrom->id,
                            'qty_stok' => $qty_stok[$i],
                            'unit_id' => $unit_id[$i],
                            'outlet_id' => $outlet_id,
                            'keterangan' => 'Transfer',
                            'user_name' => Auth::user()->name,
                        ]);
    
                        History::create([
                            'bahan_id' => $bahanTo->id,
                            'qty_stok' => $qty_stok[$i],
                            'unit_id' => $unit_id[$i],
                            'outlet_id' => $tfto,
                            'keterangan' => 'Menerima',
                            'user_name' => Auth::user()->name,
                        ]);
                    }
                } else {
                    throw new \Exception('Nama bahan dengan ID ' . $bahan_id[$i] . ' tidak tersedia di outlet asal.');
                }
            }
        });
    
        return back()->with('success', 'Transfer stok bahan berhasil.');

    }
    

    public function getBahans($outletId)
    {
        $bahans = Bahan::with('bahan_inisiasi')
            ->where('outlet_id', $outletId)
            ->get();

        return response()->json($bahans);
    }

    public function getUnittf($bahan_id)
    {
        // Menggunakan `find` untuk mencari berdasarkan `id`, jika `bahan_id` adalah primary key
        $bahan = Bahan::with('unit')->where('id', $bahan_id)->first();

        if ($bahan && $bahan->unit) {
            return response()->json([
                'unit_id' => $bahan->unit->id,
                'unit_name' => $bahan->unit->unit
            ]);
        } else {
            return response()->json([
                'unit_id' => null,
                'unit_name' => ''
            ], 404);
        }
    }
}
