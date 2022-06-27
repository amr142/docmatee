<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Doctor extends Authenticatable implements JWTSubject
{
    use Notifiable;
    public $timestamps = false;
    protected $fillable = [
        'id','name', 'email','department','union_id','password','image','email_verified_at'
    ];

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function mypatient()
    {
        return $this->hasMany(Mypatient::class);
    }
    public function prescription()
    {
        return $this->hasMany(Prescription::class);
    }
}
