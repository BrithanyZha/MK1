<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model

    

{
    use HasFactory;



    protected $table = 'menu';
    protected $fillable = ['nama_menu', 'outlet_id'];

// outlet id hrsnya gada
    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }

    public function addmenus()
    {
        return $this->hasMany(Addmenu::class, 'menu_id');
    }
    public function menuterjual()
    {
        return $this->hasMany(SoldMenu::class, 'menu_id');
    }
    

}
