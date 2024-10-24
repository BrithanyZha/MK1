<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanInisiasi extends Model
{
    use HasFactory;

    protected $table = 'bahan_inisiasi';
    protected $fillable = [
        'nama_bahan',
        'unit_id',
        'qty_inisiasi',
        'outlet_id',
        'perbungkus',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
    public function histoy()
    {
        return $this->belongsTo(History::class, 'bahan_id');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function bahans()
    {
        return $this->hasMany(Bahan::class, 'bahan_id');
    }
    public function transferbahan()
    {
        return $this->hasMany(Bahan::class, 'bahan_id');
    }
    
    public function history()
    {
        return $this->hasMany(Bahan::class, 'bahan_id','nama_bahan');
    }
}
