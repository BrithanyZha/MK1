<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Bahan;
use App\Models\Outlet;

use App\Models\History;
use Illuminate\Http\Request;
use App\Models\BahanInisiasi;
use App\Models\PurchaseOrder;
use App\Models\StokAwalHistory;
use App\Models\Historymenuterjual;

use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $outlet = Outlet::all();
        $units = Unit::all();
      
        $outlet_id = $request->input('outlet_id');

        $bahan = Bahan::where('outlet_id', $outlet_id)->get();

        $bahanInisiasi = BahanInisiasi::all();
        $selectedOutlet = $request->query('outlet_id', $outlet->first()->id ?? null);

        // dd($bahan);



        return view('stok.PurchaseOrder', compact('bahan', 'units', 'outlet', 'bahanInisiasi', 'selectedOutlet', 'outlet_id'));
    }

    public function create(Request $request)
{
    try {
        // Validate incoming request
        $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'note' => 'nullable|string|max:255',
            'bahan_id' => 'required|array',
            'bahan_id.*' => 'required|exists:bahan_inisiasi,id',
            'unit_id' => 'required|array',
            'unit_id.*' => 'required|exists:unit,id',
            'qty_order' => 'required|array',
            'qty_order.*' => 'required|numeric|min:0',
            'unit_cost' => 'required|array',
            'unit_cost.*' => 'required|numeric|min:0',
            'subtotal' => 'required|array',
            'subtotal.*' => 'required|numeric|min:0',
        ]);

        // Fetch request data
        $outlet_id = $request->outlet_id;
        $bahan_ids = $request->bahan_id;
        $unit_ids = $request->unit_id;
        $qty_orders = $request->qty_order;
        $unit_costs = $request->unit_cost;
        $subtotals = $request->subtotal;

        foreach ($bahan_ids as $index => $bahan_id) {
            // Find the existing bahan for this outlet and unit
            $findbahan = Bahan::where('bahan_id', $bahan_id)
                ->where('outlet_id', $outlet_id)
                ->where('unit_id', $unit_ids[$index])
                ->first();

            // Fetch bahan inisiasi for quantity calculations
            $bahanInisiasi = BahanInisiasi::find($bahan_id);

            if ($findbahan && $bahanInisiasi) {
                // Update the quantity stock
                $new_qty_stok = $findbahan->qty_stok + ($bahanInisiasi->qty_inisiasi * $qty_orders[$index]);
                $findbahan->update(['qty_stok' => $new_qty_stok]);

                // Create a new purchase order record
                PurchaseOrder::create([
                    'outlet_id' => $outlet_id,
                    'note' => $request->note,
                    'bahan_id' => $bahan_id,
                    'unit_id' => $unit_ids[$index],
                    'qty_order' => $qty_orders[$index],
                    'unit_cost' => $unit_costs[$index],
                    'subtotal' => $subtotals[$index],
                ]);

                // Create a history record for stock update
                History::create([
                    'bahan_id' => $bahan_id,
                    'qty_stok' => $bahanInisiasi->qty_inisiasi * $qty_orders[$index], // Store stock changes
                    'unit_id' => $unit_ids[$index],
                    'outlet_id' => $outlet_id,
                    'keterangan' => 'Add',
                    'user_name' => Auth::user()->name,
                ]);
            } else {
                // Handle the case where the bahan doesn't exist (optional logging or error handling)
                Log::warning("Bahan or BahanInisiasi not found for bahan_id: $bahan_id and outlet_id: $outlet_id");
            }
        }

        return back()->with('success', 'Bahan berhasil ditambahkan');
    } catch (\Exception $e) {
        // Log the exception for debugging purposes
        Log::error('Error in adding purchase order: ' . $e->getMessage());
        return back()->with('error', 'Terjadi kesalahan, mohon coba lagi.');
    }
}

    



    public function getBahansPo($outletId)
    {
        $bahans = Bahan::with('bahan_inisiasi')
            ->where('outlet_id', $outletId)
            ->get();

        return response()->json($bahans);
    }

    public function getUnitPo($bahanId, Request $request)
    {
        $outletId = $request->query('outlet_id');
        $bahan = Bahan::where('id', $bahanId)
            ->where('outlet_id', $outletId)
            ->first();

        if ($bahan && $bahan->unit) {
            return response()->json([
                'unit_id' => $bahan->unit->id,
                'unit_name' => $bahan->unit->unit
            ]);
        } else {
            return response()->json(['unit_id' => null, 'unit_name' => ''], 404);
        }
    }

    public function getInstock($bahanId, Request $request)
    {
        $outletId = $request->query('outlet_id');
        $bahan = Bahan::where('id', $bahanId) // Menggunakan 'id' sebagai kolom pencarian bahan_id
            ->where('outlet_id', $outletId)
            ->first();

        if ($bahan) {
            return response()->json(['qty_stok' => $bahan->qty_stok]);
        } else {
            return response()->json(['qty_stok' => 0]);
        }
    }
}
