<?php

namespace Database\Seeders;

use App\Models\Satuan;
use Illuminate\Database\Seeder;

class SatuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Satuan::create([

            'satuan' => 'gram',

        ]);
        Satuan::create([

            'satuan' => 'buah',

        ]);
    }
}
