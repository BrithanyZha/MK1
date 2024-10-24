<?php

namespace App\Models;

use App\Models\Menu;
use App\Models\Addmenu;
use App\Models\BahanInisiasi;
use App\Models\Transferbahan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Outlet extends Model
{
    use HasFactory;
    protected $table = 'outlets';
    protected $fillable = [
        'nama_outlet','address'
    ];

    public function transferbahan()
    {
        return $this->hasMany(Transferbahan::class, 'outlet_id','tfto');
    }


    public function menus()
    {
        return $this->hasMany(Menu::class, 'outlet_id');
    }

    public function addmenus()
    {
        return $this->hasMany(Addmenu::class, 'outlet_id');
    }

    public function BahanInisiasi()
    {
        return $this->hasMany(BahanInisiasi::class, 'outlet_id');
    }
    public function soldMenus()
    {
        return $this->hasMany(SoldMenu::class);
    }
    public function soldMenuHistories()
{
    return $this->hasMany(SoldMenuHistory::class, 'outlet_id');
}

}
