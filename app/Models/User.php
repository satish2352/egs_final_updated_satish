<?php

namespace App\Models;

use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    // Rest omitted for brevity

    protected $fillable = [
        // 'f_name',
        // 'm_name',
        // 'l_name',
        // 'email',
        // 'password',
        // 'number',
        // 'imei_no',
        // 'aadhar_no',
        // 'state',
        // 'district',
        // 'taluka',
        // 'village',
        // 'address',
        // 'city',
        // 'pincode',
        // 'profile_image',
        'remember_token', // Add this line to allow mass assignment
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
}