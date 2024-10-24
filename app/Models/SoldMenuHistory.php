<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldMenuHistory extends Model
{

    use HasFactory;
    
    protected $table = 'sold_menu_history';
    protected $fillable = [
        'menu_id','outlet_id','qty_mt', 'user_name'
    ];


    public function bahan()
    {
        return $this->belongsToMany(Bahan::class, 'bahan_id');
    }

    public function unit()
    {
        return $this->belongsToMany(Unit::class, 'unit_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_name', 'name');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }
    

    public function namamenu()
    {
        return $this->belongsToMany(Menu::class, 'menu_id');
    }
// In SoldMenuHistory.php model
public function menu()
{
    return $this->belongsTo(Menu::class, 'menu_id');
}

}
