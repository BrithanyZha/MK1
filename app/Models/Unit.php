<?php

namespace App\Models;

use App\Models\Bahan;
use App\Models\BahanInisiasi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model

    

{
    use HasFactory;



    protected $table = 'unit';
    protected $fillable = [
        'unit'
    ];

    public function bahans()
    {
        return $this->hasMany(Bahan::class, 'unit_id');
    }

    public function bahan_inisiasi()
    {
        return $this->hasMany(BahanInisiasi::class, 'unit_id');
    }

    public function purchaseOrder()
    {
        return $this->hasMany(BahanInisiasi::class, 'unit_id');
    }


}
