<?php

use App\Models\Unit;
use App\Models\User;
use App\Models\Outlet;
use App\Models\Addmenu;
use App\Models\MenuHistory;
use App\Models\SoldMenu;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AddmenuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuHistoryController;
use App\Http\Controllers\SoldMenuController;
use App\Http\Controllers\PerbandinganController;
use App\Http\Controllers\BahanInisiasiController;
use App\Http\Controllers\TransferbahanController;
use App\Http\Controllers\PerhitunganstokController;
use App\Http\Controllers\SoldMenuHistoryController;
use App\Http\Controllers\PurchaseOrderController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::middleware(['guest'])->group(function () {
    // tambahkan rute admin lainnya
    Route::view('/', 'auth/login');

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware(['auth'])->group(function () {
    // tambahkan rute admin lainnya
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::redirect('/home', '/user');
    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
    Route::post('/', [AuthController::class, 'logout'])->name('logout');

    Route::get('/bahan', [BahanController::class, 'index'])->name('tambah.index');
    Route::post('/bahan', [BahanController::class, 'add_bahan'])->name('tambah');

    Route::get('/bahan/get-konversi/{bahan_id}/{outlet_id}', [BahanController::class, 'get_konversi']);


    // // add satuan/Unit
    Route::get('/unit', [UnitController::class, 'index'])->name('unit');
    Route::post('/add_unit', [UnitController::class, 'add_unit'])->name('addunit');
    Route::get('/get-unit/{namaBahan}/{outletId}', [BahanController::class, 'getUnit']);

    Route::get('/listunit', [UnitController::class, 'show'])->name('listunit');

    // edit  delete satuan/Unit
    Route::get('/unit/edit/{unit}', [UnitController::class, 'edit'])->name('editunit');
    Route::put('/unit/{unit}', [UnitController::class, 'update'])->name('updateunit');
    Route::delete('/unit/{unit}', [UnitController::class, 'destroy'])->name('deleteunit');


    // list bahan
    // Route::get('/listbahan', [BahanController::class, 'show'])->name('showbahan');

    // edit  delete bahan
    Route::get('/bahan/edit/{id}', [BahanController::class, 'edit'])->name('editbahan');
    Route::put('/bahan/{id}', [BahanController::class, 'update'])->name('updatebahan');
    Route::delete('/bahan/{id}', [BahanController::class, 'destroy'])->name('deletebahan');

    Route::get('/Addbahan/get-bahans/{outletId}', [BahanController::class, 'getBahansAddbahan']);
    Route::get('/Addbahan/get-unit/{namaBahan}/{outletId}', [BahanController::class, 'getUnitAddbahan']);



    //outlet
    Route::get('/outlet', [OutletController::class, 'index'])->name('outlet');
    Route::post('/outlet/tambah', [OutletController::class, 'create'])->name('tambahoutlet');

    Route::get('/listoutlet', [OutletController::class, 'show'])->name('listoutlet');
    Route::get('/outlets/{id}/edit', [OutletController::class, 'edit'])->name('editoutlet');
    Route::put('/outlets/{id}', [OutletController::class, 'update'])->name('updateoutlet');
    Route::delete('/outlets/{id}', [OutletController::class, 'destroy'])->name('deleteoutlet');

    //transfer bahan
    Route::get('/transferbahan', [TransferbahanController::class, 'index'])->name('transferbahan');
    Route::post('/transferbahan/tambah', [TransferbahanController::class, 'create'])->name('tambahtransferbahan');
    Route::get('/listtransferbahan', [TransferbahanController::class, 'show'])->name('listtransferbahan');
    Route::get('/transferbahan/edit/{id}', [TransferbahanController::class, 'edit'])->name('edittransferbahan');
    Route::put('/transferbahan/update/{id}', [TransferbahanController::class, 'update'])->name('updatetransferbahan');
    Route::delete('/transferbahan/destroy/{id}', [TransferbahanController::class, 'destroy'])->name('deletetransferbahan');
    Route::get('/transferbahan/get-unittf/{bahanId}', [TransferbahanController::class, 'getUnittf']);
    Route::get('/get-bahans/{outletId}', [TransferbahanController::class, 'getBahans']);



    // history
    Route::get('/history', [BahanController::class, 'history'])->name('history');

    // menu
    Route::prefix('addmenu')->group(function () {
        Route::get('/', [AddmenuController::class, 'index'])->name('addmenu.index');
        Route::post('/', [AddmenuController::class, 'store'])->name('addmenu.store');
        Route::get('/list', [AddmenuController::class, 'listmenu'])->name('listmenu');
        Route::get('/menu/editresep/{menuId}', [AddmenuController::class, 'edit'])->name('menu.editresep');
        Route::put('menu/update/{menuId}', [AddmenuController::class, 'update'])->name('menu.update');
        Route::delete('/{menuId}', [AddmenuController::class, 'destroy'])->name('addmenu.destroy');
        Route::get('/details/{menuId}', [AddmenuController::class, 'show'])->name('addmenu.show');
        Route::get('/get-menus/{outletId}', [AddmenuController::class, 'getMenus']);
        Route::get('/get-bahansmenu/{outletId}', [AddmenuController::class, 'getBahansmenu']);
        Route::get('/get-unitmenu/{bahanId}', [AddmenuController::class, 'getUnitmenu']);
    });
    Route::get('/historymenu', [MenuHistoryController::class, 'index'])->name('historymenu');

    // nama menu
    Route::get('/addnamamenu', [MenuController::class, 'index'])->name('namamenu');
    Route::post('/add_namamenu', [MenuController::class, 'add_namamenu'])->name('addnamamenu');
    // edit  delete bahan
    Route::get('/namamenu/edit/{id}', [MenuController::class, 'edit'])->name('editnamamenu');
    Route::put('/namamenu/{id}', [MenuController::class, 'update'])->name('updatenamamenu');
    Route::delete('/namamenu/{id}', [MenuController::class, 'destroy'])->name('deletenamamenu');


    // edit  delete nama menu
    Route::get('/namamenu/edit}', [MenuController::class, 'edit'])->name('editnamamenu');
    Route::put('/namamenu/update', [MenuController::class, 'update'])->name('updatenamamenu');
    Route::delete('/namamenu/delete', [MenuController::class, 'destroy'])->name('deletenamamenu');


    // Menu Terjual Routes
    Route::get('/menuterjual', [SoldMenuController::class, 'index'])->name('menuterjual');
    // Route::get('/historymenuterjual', [MenuterjualController::class, 'show'])->name('menuterjual.show');
    Route::post('/menuterjualcreate', [SoldMenuController::class, 'create'])->name('menuterjual.create');
    Route::get('/menuterjual/get-menus/{outletId}', [SoldMenuController::class, 'getMenus']);
    Route::delete('/menuterjual/delete/{soldMenu}', [SoldMenuController::class, 'destroy'])->name('menuterjual.delete');

    Route::get('/sold-menu/edit/{soldMenu}', [SoldMenuController::class, 'edit'])->name('sold_menu.edit');
    Route::put('/sold-menu/update/{soldMenu}', [SoldMenuController::class, 'update'])->name('sold-menu.update');

    // History Menu Terjual
    Route::get('/historymenuterjual', [SoldMenuHistoryController::class, 'index'])->name('historymt');
    Route::get('/historymenuterjual/all', [SoldMenuHistoryController::class, 'show'])->name('historymtshow');



    // Perbandingan
    Route::get('/perbandingan', [PerbandinganController::class, 'index'])->name('perbandingan');

    // kurangi
    Route::get('/bahankurang', [BahanController::class, 'bkurangview'])->name('kurangview');
    Route::post('/bahankurang', [BahanController::class, 'bkurang'])->name('kurang');
    Route::get('/bahankurang/get-bahans/{outletId}', [BahanController::class, 'getBahans']);
    Route::get('/bahankurang/get-unit/{namaBahan}/{outletId}', [BahanController::class, 'getUnit']);
    


    // search
    Route::get('/search', [SearchController::class, 'search'])->name('search');

    // bahan inisiasi

    Route::get('/bahanInisiasi', [BahanInisiasiController::class, 'index'])->name('bahan_inisiasi');
    Route::post('/add_bahanInisiasi', [BahanInisiasiController::class, 'store'])->name('add_bahan_inisiasi');

    Route::get('/list_bahan_inisiasi', [BahanInisiasiController::class, 'show'])->name('listbahan_inisiasi');

    // edit  delete satuan/Unit
    Route::get('/bahan_inisiasi/edit/{id}', [BahanInisiasiController::class, 'edit'])->name('editbahan_inisiasi');
    Route::put('/bahan_inisiasi/{id}', [BahanInisiasiController::class, 'update'])->name('updatebahan_inisiasi');
    Route::delete('/bahan_inisiasi/{id}', [BahanInisiasiController::class, 'destroy'])->name('deletebahan_inisiasi');

    // Purchase Order
    Route::get('/PurchaseOrder', [PurchaseOrderController::class, 'index'])->name('purchaseOrder');
    Route::post('/PurchaseOrder/tambah', [PurchaseOrderController::class, 'create'])->name('tambahpurchaseOrder');
    Route::get('/puchaseOrder/get-bahansPo/{outletId}', [PurchaseOrderController::class, 'getBahansPo']);
    Route::get('/purchaseOrder/get-unit/{bahanId}', [PurchaseOrderController::class, 'getUnitPo']);
    Route::get('/purchaseOrder/get-instock/{bahanId}', [PurchaseOrderController::class, 'getInstock']);
    // Route::get('/listPurchaseOrdern', [TransferbahanController::class, 'show'])->name('listtransferbahan');
    // Route::get('/PurchaseOrder/edit/{id}', [PurchaseOrderController::class, 'edit'])->name('edittransferbahan');
    // Route::put('/PurchaseOrder/update/{id}', [PurchaseOrderController::class, 'update'])->name('updatetransferbahan');
    // Route::delete('/PurchaseOrder/destroy/{id}', [PurchaseOrderController::class, 'destroy'])->name('deletetransferbahan');
    // Route::get('/purchaseOrder/get-konversi/{bahan_id}/{outlet_id}', [PurchaseOrderController::class, 'get_konversi']);
    
    
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Route::post('/dashoutlet', [DashboardController::class, 'tambah'])->name('dashoutlet');


    // Route::get('/get-bahan-outlet/{outletId}', [BahanController::class, 'getBahanByOutlet']);
});

// Route::middleware(['auth', 'user'])->group(function () {
//     Route::get('/editbahan/{id}', [BahanController::class, 'editBahan'])->name('editbahan');
//     Route::delete('/deletebahan/{id}', [BahanController::class, 'deleteBahan'])->name('deletebahan');
// });


// bahan
// Route::get('/tambahstok',[BahanController::class,'index'])->name('tambahstok');
