<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historymenu extends Model
{

    use HasFactory;
    
    protected $table = 'historymenu';
    protected $fillable = [
        'nama_menu', 'nama_bahan', 'jml_takaran', 'satuan', 'keterangan', 'user_name'
    ];


    public function bahan()
    {
        return $this->belongsTo(Bahan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
