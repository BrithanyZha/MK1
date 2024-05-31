
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerhitunganTable extends Migration
{
    public function up()
    {
        Schema::create('perhitunganstoks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->integer('jml_sisa');
            $table->string('satuan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('perhitunganstoks');
    }
}
