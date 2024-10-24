<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BahanInisiasi extends Migration
{
    public function up()
    {
        Schema::create('bahan_inisiasi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bahan');
            $table->float('qty_inisiasi');
            $table->foreignId('outlet_id')->constrained('outlets');
            $table->foreignId('unit_id')->constrained('unit');
            // $table->string('perbungkus');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bahan_inisiasi');
    }
}
