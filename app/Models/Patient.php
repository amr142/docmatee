<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Patient extends Authenticatable implements JWTSubject ,MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table='patients';
    protected $fillable = [
        'name', 'address', 'date','phone','id','password','image','email','blood_type','weight','height','otp'
    ];
    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
         'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



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

    public function vaccine()
    {
        return $this->hasMany(Vaccine::class);
    }
    public function lab()
    {
        return $this->hasMany(Lab::class);
    }
    public function radiology()
    {
    return $this->hasMany(Radiology::class);
    }
    public function surgery()
    {
        return $this->hasMany(Surgery::class);
    }
        public function glucos()
    {
        return $this->hasMany(Bloodglucos::class);
    }
    public function prescription()
    {
        return $this->hasMany(Prescription::class);
    }
    public function pressure()
    {
        return $this->hasMany(Bloodpressure::class);
    }
    public function allergy()
    {
        return $this->hasMany(Allergy::class);
    }
    public function familyhistory()
    {
        return $this->hasMany(FamilyHistory::class);
    }
}
