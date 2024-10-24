<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transferbahan extends Model
{
    use HasFactory;

    protected $table = 'transfer_bahan';

    protected $fillable = [
        'outlet_id',
        'tfto',
        'note',
        'bahan_id',
        'qty_stok',
        'unit_id',
    ];

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id','qty_stok');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class,'outlet_id');
    }

    public function outletTo()
    {
        return $this->belongsTo(Outlet::class, 'tfto');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
    public function bahan_inisiasi()
    {
        return $this->belongsTo(BahanInisiasi::class, 'bahan_id');
    }
    
}
