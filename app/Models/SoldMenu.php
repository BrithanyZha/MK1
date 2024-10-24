<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id','outlet_id','qty_mt', 'user_name'
    ];

    protected $table = 'sold_menu';
        // relasi ke tabel addmenu

    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_name', 'name');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function resep()
    {
        return $this->hasMany(Addmenu::class, 'menu_id' ,'bahan_id', 'outlet_id','qty_takaran');
    }
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
