<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorymenuTable extends Migration
{
    public function up()
    {
        Schema::create('menu_history', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('outlet_id')->constrained('outlets');
            $table->foreignId('menu_id')->constrained('menu');
            $table->foreignId('bahan_id')->constrained('bahan_inisiasi');
            $table->integer('qty_takaran');
            $table->foreignId('unit_id')->constrained('unit');
            $table->text('keterangan');
            $table->string('user_name');
            $table->timestamps();
          
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_history');
    }
}

