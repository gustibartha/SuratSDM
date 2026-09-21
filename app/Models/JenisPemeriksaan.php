<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPemeriksaan extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'jenis_pemeriksaans';

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
    protected $fillable = ['jenis_pemeriksaan', 'keterangan'];

    

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
        return $this->hasOne('App\Models\FormJaminan');
    }
}
