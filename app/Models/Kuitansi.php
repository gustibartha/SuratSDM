<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Kuitansi extends Model
{
    protected $table = 'kuitansis';

    protected $fillable = [
        'karyawan_id',
        'created_by',
        'nama_pasien',
        'hubungan_keluarga',
        'id_rumah_sakit',
        'nominal',
        'tanggal_kuitansi',
        'diagnosa',
        'foto_path',
        'status',
        'catatan',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id')->withTrashed();
    }

    public function rumahSakit()
    {
        return $this->belongsTo(RumahSakit::class, 'id_rumah_sakit', 'id');
    }

    /**
     * Named "creator" (not "createdBy") so its JSON key doesn't collide
     * with the raw created_by foreign-key column.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
