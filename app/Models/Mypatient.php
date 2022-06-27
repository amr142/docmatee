<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mypatient extends Model
{
    protected $table='my_patient';
    protected $fillable = [
        'id','doctor_id','patient_id'
    ];
    public $timestamps = false;

}
