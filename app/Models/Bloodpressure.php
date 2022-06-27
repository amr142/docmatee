<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bloodpressure extends Model
{
    protected $table='blood_pressure';
    protected $fillable = [
        'id', 'systolic_pressure', 'diastolic_pressure','date','time','patient_id'
    ];
    public $timestamps = false;

}
