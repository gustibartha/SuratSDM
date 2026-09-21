<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJenisPemeriksaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jenis_pemeriksaans', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('jenis_pemeriksaan')->nullable();
            $table->text('keterangan')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('jenis_pemeriksaans');
    }
}
