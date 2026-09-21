<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelasRawatInap extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'kelas_rawat_inaps';

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
    protected $fillable = ['jenis_kelas', 'harga'];

    

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

    public function formjaminan()
    {
        return $this->hasOne('App\Models\FormJaminan');
    }
    public function karyawan()
    {
        return $this->hasOne('App\Models\Karyawan')->withTrashed();
    }
}
