<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormJaminan extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'form_jaminans';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['nomor_surat', 'jenis_surat', 'id_karyawan', 'id_instansi', 'id_jenis_pemeriksaan','nama_pasien','hubungan_keluarga', 'id_rumah_sakit', 'biaya_rumah_sakit', 'status_pengajuan', 'status_email','file_pdf','is_rejected'];

    

    /**
     * Change activity log event description
     *
     * @param string $eventName
     *
     * @return string
     */
    public function getDescriptionForEvent($eventName)
    {
        return __CLASS__ . " model has been {$eventName}";
    }
    public function kelasRawatInap() 
    { 
        return $this->belongsTo('App\Models\KelasRawatInap','id_kelas_rawat_inap','id'); 
    }
    public function rumahSakit() 
    { 
        return $this->belongsTo('App\Models\RumahSakit','id_rumah_sakit','id'); 
    }
    public function jenisPemeriksaan() 
    { 
        return $this->belongsTo('App\Models\JenisPemeriksaan','id_jenis_pemeriksaan','id'); 
    }
    public function karyawan() 
    { 
        return $this->belongsTo('App\Models\Karyawan','id_karyawan','id')->withTrashed();
    }
    public function monitoringTagihan() 
    { 
        return $this->hasMany('App\Models\MonitoringTagihan'); 
    }
}
