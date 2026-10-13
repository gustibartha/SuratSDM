<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKuitansiNameAliasesTable extends Migration
{
    public function up()
    {
        Schema::create('kuitansi_name_aliases', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status_karyawan', 32);
            $table->string('alias_key');
            $table->string('alias_name');
            $table->unsignedInteger('karyawan_id');
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();

            $table->unique(['status_karyawan', 'alias_key']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('kuitansi_name_aliases');
    }
}
