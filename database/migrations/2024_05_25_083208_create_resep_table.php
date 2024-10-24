<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResepTable extends Migration
{
    public function up()
    {
        Schema::create('resep', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menu')->onDelete('cascade');
            $table->foreignId('outlet_id')->constrained('outlets');
            $table->foreignId('bahan_id')->constrained('bahan_inisiasi');
            $table->float('qty_takaran');
            $table->foreignId('unit_id')->constrained('unit');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('resep');
    }
}
