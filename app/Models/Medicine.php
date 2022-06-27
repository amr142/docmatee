<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    protected $table='medicines';
    protected $fillable = [
        'id', 'name', 'prescription_id'
    ];
    public $timestamps = false;
    public function prescription(){
        return$this->belongsTo(Prescription::class);
    }
}
