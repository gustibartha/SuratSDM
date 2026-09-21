<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryRecord extends Model
{
    protected $table = 'history_records';

    protected $fillable = [
        'karyawan_id',
        'riwayat_penyakit',
        'jenis_pengobatan',
        'riwayat_obat',
        'resume_medis'
    ];

    public function karyawan()
    {
        return $this->hasOne('App\Models\Karyawan', 'id', 'karyawan_id')->withTrashed();
    }
}
