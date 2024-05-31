<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perhitunganstok extends Model
{
    use HasFactory;

    protected $table = 'perhitunganstoks';
    protected $fillable = ['nama_barang', 'jml_sisa', 'satuan'];
}
