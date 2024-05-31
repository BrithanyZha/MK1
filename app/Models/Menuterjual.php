<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menuterjual extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_menu',
        'jumlah',
        'user_name'
    ];

    protected $table = 'menu_terjuals';
        // relasi ke tabel addmenu
        public function menuterjual()
        {
            return $this->belongsTo(Addmenu::class);
        }
}
