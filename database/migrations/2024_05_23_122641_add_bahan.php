<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBahan extends Migration
{
    public function up()
    {
        Schema::create('bahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bahan_id')->constrained('bahan_inisiasi')->onDelete('cascade');
            $table->float('qty_stok');
            $table->foreignId('outlet_id')->constrained('outlets');
            $table->foreignId('unit_id')->constrained('unit');
            $table->string('user_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bahan');
    }
}
