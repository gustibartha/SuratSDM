<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKuitansisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kuitansis', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('karyawan_id');
            $table->unsignedInteger('created_by')->nullable();
            $table->string('nama_pasien');
            $table->string('hubungan_keluarga')->default('Ybs');
            $table->unsignedInteger('id_rumah_sakit')->nullable();
            $table->bigInteger('nominal')->nullable();
            $table->date('tanggal_kuitansi')->nullable();
            $table->text('diagnosa')->nullable();
            $table->string('foto_path')->nullable();
            $table->string('status')->default('Diajukan');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index('karyawan_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kuitansis');
    }
}
