<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuHistory extends Model
{

    use HasFactory;

    protected $table = 'menu_history';
    protected $fillable = [
        'outlet_id',
        'menu_id',
        'bahan_id',
        'qty_takaran',
        'unit_id',
        'keterangan',
        'user_name'
    ];


    public function bahan()
    {
        return $this->belongsTo(Bahan::class, 'bahan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }
    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
