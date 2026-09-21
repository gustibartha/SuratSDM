<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonitoringTagihan extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'monitoring_tagihans';

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
    protected $fillable = ['id_form_jaminan', 'tanggal_tagihan', 'no_tagihan','jumlah', 'tanggal_pembayaran', 'tanggal_realisasi_perawatan','tanggal_realisasi_perawatan_akhir', 'keterangan', 'status_pembayaran'];

    

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
    public function formJaminan()
    {
        return $this->belongsTo('App\Models\FormJaminan','id_form_jaminan','id'); 
    }
}
