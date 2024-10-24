<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Bahan;
use App\Models\Outlet;
use App\Models\Addmenu;
use App\Models\SoldMenu;
use Illuminate\Http\Request;
use App\Models\SoldMenuHistory;
use Illuminate\Support\Facades\Auth;

class SoldMenuController extends Controller
{
    public function index(Request $request)
    {
        $outlet_id = $request->input('outlet_id');
        $outlet = Outlet::all();
        $menus = Menu::where('outlet_id', $outlet_id)->get();

        // Aggregate sold menus by menu_id and outlet_id
        $soldMenus = SoldMenu::select('menu_id', 'outlet_id', SoldMenu::raw('SUM(qty_mt) as total_qty'))
                             ->where('outlet_id', $outlet_id)
                             ->groupBy('menu_id', 'outlet_id')
                             ->with(['menu', 'outlet'])
                             ->get();

        // Retrieve user details for each sold menu item
        $userDetails = SoldMenu::select('qty_mt','menu_id', 'outlet_id', 'user_name', 'created_at')
                               ->where('outlet_id', $outlet_id)
                               ->get()
                               ->groupBy('menu_id');

        return view('menu.menuterjual', compact('outlet', 'menus', 'soldMenus', 'userDetails', 'outlet_id'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|array',
            'menu_id.*' => 'required|exists:menu,id',
            'outlet_id' => 'required|exists:outlets,id',
            'qty_mt' => 'required|array',
            'qty_mt.*' => 'required'
        ]);

        foreach ($request->menu_id as $key => $menu_id) {
            SoldMenu::create([
                'outlet_id' => $request->outlet_id,
                'menu_id' => $menu_id,
                'qty_mt' => $request->qty_mt[$key],
                'user_name' => Auth::user()->name,
            ]);

            // Add to history
            SoldMenuHistory::create([
                'outlet_id' => $request->outlet_id,
                'menu_id' => $menu_id,
                'qty_mt' => $request->qty_mt[$key],
                'user_name' => Auth::user()->name,
            ]);

            // Update stock
            $addmenus = Addmenu::where('menu_id', $menu_id)
                               ->where('outlet_id', $request->outlet_id)
                               ->get();

            foreach ($addmenus as $addmenu) {
                $bahan = Bahan::find($addmenu->bahan_id);
                if ($bahan) {
                    $sisa = $bahan->qty_stok - ($addmenu->qty_takaran * $request->qty_mt[$key]);
                    $bahan->update(['qty_stok' => $sisa]);
                }
            }
        }

        return redirect()->route('menuterjual', ['outlet_id' => $request->outlet_id]);
    }

    public function destroy($soldMenuId)
    {
        // Fetch the sold menu record by its ID
        $soldMenu = SoldMenu::findOrFail($soldMenuId);

        // Fetch the ingredients related to the sold menu
        $addmenus = Addmenu::where('menu_id', $soldMenu->menu_id)
                           ->where('outlet_id', $soldMenu->outlet_id)
                           ->get();

        foreach ($addmenus as $addmenu) {
            // Find the related bahan (ingredient)
            $bahan = Bahan::find($addmenu->bahan_id);

            if ($bahan) {
                // Restore stock: Multiply the quantity used per menu item by the quantity sold
                $restoreAmount = $addmenu->qty_takaran * $soldMenu->qty_mt;
                $bahan->update(['qty_stok' => $bahan->qty_stok + $restoreAmount]);
            }
        }

        // Now delete the sold menu record
        $soldMenu->delete();

        return response()->json(['success' => 'Menu deleted and stock restored successfully']);
    }

    public function getMenus($outletId)
    {
        $menus = Menu::where('outlet_id', $outletId)->get();
        return response()->json($menus);
    }
}
