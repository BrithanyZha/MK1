<?php

use App\Models\User;
use App\Models\Satuan;
use App\Models\Historymenu;
use App\Models\Menuterjual;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\AddmenuController;
use App\Http\Controllers\HistorymenuController;
use App\Http\Controllers\MenuterjualController;
use App\Http\Controllers\PerhitunganstokController;
use App\Http\Controllers\HistorymenuterjualController;

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
    Route::view('/','auth/login' );
    
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware(['auth'])->group(function () {
    // tambahkan rute admin lainnya
    Route::redirect('/home','/user');
    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
    Route::post('/', [AuthController::class, 'logout'])->name('logout');

    Route::get('/bahan',[BahanController::class,'index']);
    Route::post('/bahan',[BahanController::class,'add_bahan'])->name('tambah');

    // add satuan
    Route::get('/satuan',[SatuanController::class,'index']);
    Route::post('/satuan',[SatuanController::class,'add_satuan'])->name('satuan');

    // list bahan
    Route::get('/listbahan',[BahanController::class,'show'])->name('showbahan');
    // edit  delete bahan
    // Route::get('/listbahan', [BahanController::class, 'index'])->name('listbahan');
    Route::get('/bahan/edit/{id}', [BahanController::class, 'edit'])->name('editbahan');
    Route::put('/bahan/{id}', [BahanController::class, 'update'])->name('updatebahan');
    Route::delete('/bahan/{id}', [BahanController::class, 'destroy'])->name('deletebahan');
        


    // Route::post('/addmenu', [AddmenuController::class, 'store'])->name('addmenu');
    Route::get('/perhitunganstok', [PerhitunganstokController::class, 'index'])->name('perhitunganstok.index');
    // history
    Route::get('/history', [BahanController::class,'history'])->name('history');
    
    // menu
    Route::get('/addmenu', [AddmenuController::class, 'index'])->name('addmenu.index');
    Route::post('/addmenu', [AddmenuController::class, 'store'])->name('addmenu.store');
    Route::get('/listmenu', [AddmenuController::class, 'listmenu'])->name('listmenu');
    Route::get('/addmenu/{addmenu}/edit', [AddmenuController::class, 'edit'])->name('addmenu.edit');
    Route::put('/addmenu/{addmenu}', [AddmenuController::class, 'update'])->name('addmenu.update');
    Route::delete('/addmenu/{addmenu}', [AddmenuController::class, 'destroy'])->name('addmenu.destroy');
    Route::get('/historymenu',[HistorymenuController::class, 'index'])->name('historymenu');

    // Menu Terjual Routes
    Route::get('/menuterjual', [MenuterjualController::class, 'index'])->name('menuterjual');
    Route::get('/historymenuterjual', [MenuterjualController::class, 'show'])->name('menuterjual.show');
    Route::post('/menuterjualcreate', [MenuterjualController::class, 'create'])->name('menuterjual.create');

    // History Menu Terjual Routes
    // Route::get('/historymenuterjual', [HistorymenuterjualController::class, 'show'])->name('historymenuterjual');


});

// bahan
// Route::get('/tambahstok',[BahanController::class,'index'])->name('tambahstok');



