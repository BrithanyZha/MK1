<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Searchhimenu extends Model
{
    use HasFactory;
    
    protected $table = 'bahan';
    public function searchhimenu ($query, array $filters)
    {
        if($filters['search']?? false){
            $query-> where ('nama_menu','like','%'.request('search').'%')->get();
            $query-> where ('nama_bahan','like','%'.request('search').'%')->get();
            $query-> where ('jml_takaran','like','%'.request('search').'%')->get();
            $query-> where ('satuan','like','%'.request('search').'%')->get();
            $query-> where ('keterangan','like','%'.request('search').'%')->get();
            $query-> where ('user_name','like','%'.request('search').'%')->get();

        }else{
            return ('kata kunci tidak tersedia');
        }
    }

}
