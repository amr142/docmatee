<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyHistory extends Model
{
    protected $table = 'family_history';
    protected $fillable = [
        'id', 'disease', 'realation', 'patient_id'
    ];
    public $timestamps = false;
}
