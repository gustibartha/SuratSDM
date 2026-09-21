<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisaKeluarga extends Model
{
    protected $table = 'visa_keluargas';

    protected $guarded =['id'];

    public function karyawan()
    {
        return $this->hasOne('App\Models\Karyawan', 'id', 'karyawan_id')->withTrashed();
    }
}
