<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMonitoringTagihansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monitoring_tagihans', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('id_form_jaminan')->nullable();
            $table->date('tanggal_tagihan')->nullable();
            $table->string('no_tagihan')->nullable();
            $table->date('tanggal_pembayaran')->nullable();
            $table->date('tanggal_realisasi_perawatan')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('status_pembayaran')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('monitoring_tagihans');
    }
}
