<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $table = 'history';
    protected $fillable = [
        'bahan_id', 'outlet_id', 'unit_id', 'qty_stok', 'user_name', 'keterangan'
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

    public function bahan_inisiasi()
    {
        return $this->belongsTo(BahanInisiasi::class, 'bahan_id');
    }

    // public function scopeFilter($query, array $filters)
    // {
    //     if ($filters['search'] ?? false) {
    //         $query->where(function ($query) use ($filters) {
    //             $query->where('nama_bahan', 'like', '%' . $filters['search'] . '%')
    //                 ->orWhere('outlet_id', function ($query) use ($filters) {
    //                     $query->select('id')->from('outlets')->where('nama_outlet', 'like', '%' . $filters['search'] . '%');
    //                 })
    //                 ->orWhere('satuan', 'like', '%' . $filters['search'] . '%')
    //                 ->orWhere('qty_stok', 'like', '%' . $filters['search'] . '%')
    //                 ->orWhere('user_name', 'like', '%' . $filters['search'] . '%')
    //                 ->orWhere('keterangan', 'like', '%' . $filters['search'] . '%');
    //         });
    //     }
    // }
}