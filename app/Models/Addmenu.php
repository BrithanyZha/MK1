<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addmenu extends Model
{
    use HasFactory;

    protected $table = 'addmenus';
    protected $fillable = ['nama_menu', 'nama_bahan', 'jml_takaran', 'satuan'];

    protected $casts = [
        'nama_bahan' => 'array',
        'jml_takaran' => 'array',
        'satuan' => 'array',
    ];
    // Relasi ke tabel bahan
    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'nama_bahan', 'nama_bahan','satuan');
    }
}
