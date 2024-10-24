<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoldMenuHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sold_menu_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menu');
            $table->foreignId('outlet_id')->constrained('outlets');
            $table->float('qty_mt');
            $table->string('user_name');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::dropIfExists('sold_menu_history');

    }
}
