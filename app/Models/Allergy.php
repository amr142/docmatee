<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allergy extends Model
{
    protected $table='allergy';
    protected $fillable = [
        'id','allergy','patient_id'
    ];
    public $timestamps = false;
}
