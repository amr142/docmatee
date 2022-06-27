<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Radiology extends Model
{
    protected $table='radiology';
    protected $fillable = [
        'id', 'name', 'image', 'type','location','date','patient_id'
    ];
    public $timestamps = false;
}
