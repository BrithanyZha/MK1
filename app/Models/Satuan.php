<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model

    

{
    use HasFactory;



    protected $table = 'satuan';
    protected $fillable = [
        'satuan'
    ];

    // relasi ke tabel bahan
    // public function bahan()
    // {
    //     return $this->hasMany(Bahan::class);
    // }
}
