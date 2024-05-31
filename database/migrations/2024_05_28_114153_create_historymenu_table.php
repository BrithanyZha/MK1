<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorymenuTable extends Migration
{
    public function up()
    {
        Schema::create('historymenu', function (Blueprint $table) {
            $table->id();
            $table->string('nama_menu');
            $table->json('nama_bahan');
            $table->json('jml_takaran');
            $table->json('satuan');
            $table->text('keterangan');
            $table->string('user_name');
            // $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('historymenu');
    }
}

