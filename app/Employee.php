<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Employee extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'employees';
    protected $primaryKey = 'id_employee';
    protected $keyType = 'string';

    protected $fillable = [
        'id_employee', 'id_outlet', 'id_user', 'name_employee','role', 'status'
    ];


    public function user()
    {
        return $this->belongsTo('App\User', 'foreign_key');
    }

    public function outlet()
    {
        return $this->belongsTo('App\Outlet', 'foreign_key');
    }

    public function loginSessions()
    {
        return $this->hasOne('App\LoginSessions', 'foreign_key');
    }
}
