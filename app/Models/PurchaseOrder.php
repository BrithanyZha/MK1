<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $table = 'purchase_order';
    protected $fillable = [
        'outlet_id',
        'note',
        'bahan_id',
        'unit_id',
        'qty_order',
        'unit_cost',
        'subtotal',

    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
    // public function bahan()
    // {
    //     return $this->belongsToMany(Bahan::class, 'bahan_id');
    // }
    public function bahanInisiasi()
    {
        return $this->belongsToMany(Bahan::class, 'bahan_id');
    }
    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id');
    }
}
