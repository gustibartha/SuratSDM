<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTablesRawatInaps extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kelas_rawat_inaps', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('jenis_kelas')->nullable();
            $table->integer('harga')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('kelas_rawat_inaps');
    }
}
