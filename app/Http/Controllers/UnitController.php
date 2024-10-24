<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Models\Outlet;
use Illuminate\Validation\Rule;


class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function index(){
        $units = Unit::all();
        $selectedOutlet = Outlet::all();

        return view('stok.unit', compact('units', 'selectedOutlet'));
     }
    // public function show()
    // {
    //     $units = Unit::all();
    //     return view('stok.listunit', compact('units'));
    // }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_unit(Request $request)
    {
        //
        // Debugging untuk memastikan request diterima
    
        $request->validate([

            'unit' => 'required|unique:unit',

        ]);
    
        Unit::create([
            'unit' => $request->unit

        ]);
    
        return back()->with('success','Unit has been successfully added.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUnitRequest  $request
     * @return \Illuminate\Http\Response
     */
    
    public function edit(Request $request, $id)
    {
        $outlet_id = $request->input('outlet_id');
       
        $outlet = Outlet::all();
        $units = Unit::findOrFail($id);
        $selectedOutlet = $request->query('outlet_id', $outlet->first()->id ?? null);

        return view('stok.editunit', compact('units','outlet', 'selectedOutlet', 'outlet_id'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUnitRequest  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validasi dengan aturan unik tetapi pengecualian untuk unit yang sedang diedit
        $request->validate([
            'unit' => [
                'required',
                'string',
                'max:255',
                Rule::unique('unit')->ignore($id), // Abaikan unit yang sedang diedit
            ],
        ]);
    
        // Temukan unit berdasarkan ID dan update
        $unit = Unit::findOrFail($id);
        $unit->update([    
            'unit' => $request->unit
        ]);
    
        // Redirect kembali ke halaman unit dengan pesan sukses
        return redirect()->route('unit')->with('success', 'Unit berhasil diperbarui.');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unit $unit)
    {

        $unit->delete();

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('unit')
                         ->with('success', 'Bahan berhasil dihapus.');
    
    
    }
}
