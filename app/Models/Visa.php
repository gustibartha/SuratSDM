<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visa extends Model
{
    protected $table = 'visas';

    protected $guarded =['id'];

    public function karyawan()
    {
        return $this->hasOne('App\Models\Karyawan', 'id', 'karyawan_id')->withTrashed();
    }
    public function keluarga()
    {
        return $this->hasMany('App\Models\VisaKeluarga', 'visa_id', 'id');
    }
}
