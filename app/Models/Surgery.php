<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surgery extends Model
{
    protected $table='surgery';
    protected $fillable = [
        'id', 'name', 'type','location','date','image','patient_id'
    ];
    public $timestamps = false;
}
