<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    protected $table='vaccine';
    protected $fillable = [
        'id', 'name', 'type','location', 'date','image','patient_id',
    ];
    public $timestamps = false;

}
