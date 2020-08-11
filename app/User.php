<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'id_user';
    protected $keyType = 'string';

    protected $fillable = [
        'id_user', 'name_user', 'username', 'email_user', 'password_user', 'phone_user', 'photo_user', 'status'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password_user',
    ];

    public function employee()
    {
        return $this->hasOne('App\Employee', 'foreign_key');
    }

    public function registerToken()
    {
        return $this->hasOne('App\RegisterToken', 'foreign_key');
    }

    public function owner()
    {
        return $this->hasOne('App\Owner', 'foreign_key');
    }

    public function loginSessions()
    {
        return $this->hasOne('App\LoginSessions', 'foreign_key');
    }
    
}
