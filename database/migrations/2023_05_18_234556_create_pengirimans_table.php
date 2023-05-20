<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePengirimansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengirimans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('no_pengiriman', 15);
            $table->date('tanggal');
            $table->integer('lokasi_id')->nullable();
            $table->integer('barang_id')->nullable();
            $table->integer('jumlah_barang');
            $table->integer('harga_barang')->nullable();
            $table->integer('kurir_id')->nullable();
            $table->string('status')->default('WAITING');
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
        Schema::dropIfExists('pengirimans');
    }
}
