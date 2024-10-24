<?php

namespace App\Models;

use App\Models\Bahan;
use App\Models\Outlet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Addmenu extends Model
{
    use HasFactory;

    protected $table = 'resep';
    protected $fillable = ['menu_id', 'nama_menu', 'outlet_id', 'bahan_id', 'qty_takaran', 'unit_id'];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id','qty_stok');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function details()
    {
        return $this->hasMany(Addmenu::class, 'menu_id');
    }

    public function soldMenu()
    {
        return $this->hasMany(SoldMenu::class, 'menu_id', 'outlet_id', 'qty_mt');
    }
    public function bahan_inisiasi()
    {
        return $this->belongsTo(BahanInisiasi::class, 'bahan_id');
    }
}
