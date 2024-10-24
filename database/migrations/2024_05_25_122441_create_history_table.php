<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('history', function (Blueprint $table) {

            $table->id();
            $table->foreignId('bahan_id')->constrained('bahan');
            $table->foreignId('outlet_id')->constrained('outlets');
            $table->foreignId('unit_id')->constrained('unit');
            $table->float('qty_stok');
            $table->text('keterangan');
            $table->string('user_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('history');
    }
}
