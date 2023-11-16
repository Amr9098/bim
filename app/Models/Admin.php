<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles,SoftDeletes;

    protected $guard="admin";
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $fillable = [
        "name",
        "email",
        "password",

    ];
    protected $hidden = [
        "password",
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

   public function setPasswordAttribute($password)
   {
       if (!empty($password)) {
           $this->attributes['password'] = bcrypt($password);
       }
   }

}
