<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddmenusTable extends Migration
{
    public function up()
    {
        Schema::create('addmenus', function (Blueprint $table) {
            $table->id();
            $table->string('nama_menu')->unique();
            $table->json('nama_bahan');
            $table->json('jml_takaran');
            $table->json('satuan');
            $table->timestamps();
        });
    }

    public function down()
    {
        // Schema::dropIfExists('perhitunganstks');
        Schema::dropIfExists('addmenus');
    }
}