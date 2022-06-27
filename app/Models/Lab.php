<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    protected $table='lab_tests';
    protected $fillable = [
        'id', 'name', 'location','date','type','image','patient_id'
    ];
    public $timestamps = false;
}
