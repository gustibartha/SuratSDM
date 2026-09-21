<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratKeterangan extends Model
{
    protected $table = 'surat_keterangans';

    protected $guarded =['id'];

    public function karyawan()
    {
        return $this->hasOne('App\Models\Karyawan', 'id', 'karyawan_id');
    }
}
