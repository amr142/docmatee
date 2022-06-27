<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bloodglucos extends Model
{
    protected $table='blood_glucos';
    protected $fillable = [
        'id','glucos_result','glucos_type','date','time','patient_id'
    ];
    public $timestamps = false;

}
