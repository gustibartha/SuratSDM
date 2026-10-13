<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Admin-confirmed link from a name as spelled in the kuitansi Google Sheet
 * to the karyawan record it refers to, for spellings the sync can't match
 * on its own (e.g. "KOESPRAPTINI RIA" in the sheet vs "KOESPRATINI RIA").
 */
class KuitansiNameAlias extends Model
{
    protected $table = 'kuitansi_name_aliases';

    protected $fillable = ['status_karyawan', 'alias_key', 'alias_name', 'karyawan_id', 'created_by'];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }
}
