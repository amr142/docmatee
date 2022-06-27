<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $table='prescription';
    protected $fillable = [
        'id', 'Prescription_photo', 'notes','summary','doctor_id','date','patient_id'
    ];
    public $timestamps = false;
    public function medicine()
    {
        return $this->hasMany(Medicine::class);
    }

}
