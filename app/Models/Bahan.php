<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bahan extends Model
{
    use HasFactory;

    protected $table = 'bahan';
    protected $fillable = [
        'bahan_id', 'unit_id', 'qty_stok', 'outlet_id', 'user_name'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
    public function history()
    {
        return $this->belongsTo(History::class, 'bahan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_name', 'name');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    
    public function transferbahan()
    {
        return $this->hasMany(Transferbahan::class, 'bahan_id' , 'unit_id','qty_stok');
    }

    public function addmenu()
    {
        return $this->hasMany(Addmenu::class, 'bahan_id' , 'unit_id','qty_stok');
    }
    
    public function menuterjual()
    {
        return $this->hasMany(SoldMenu::class, 'menu_id' ,'bahan_id', 'outlet_id','qty_takaran');
    }
    
    public function bahan_inisiasi()
    {
        return $this->belongsTo(BahanInisiasi::class, 'bahan_id');
    }


    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'bahan_id');
    }
}