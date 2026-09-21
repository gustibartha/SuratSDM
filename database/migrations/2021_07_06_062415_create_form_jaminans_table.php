<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFormJaminansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_jaminans', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('nomor_surat')->nullable();
            $table->string('jenis_surat')->nullable();
            $table->integer('id_karyawan')->nullable();
            $table->integer('id_instansi')->nullable();
            $table->integer('id_jenis_pemeriksaan')->nullable();
            $table->integer('id_rumah_sakit')->nullable();
            $table->integer('biaya_rumah_sakit')->nullable();
            $table->string('status_pengajuan')->nullable();
            $table->boolean('status_email')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('form_jaminans');
    }
}
