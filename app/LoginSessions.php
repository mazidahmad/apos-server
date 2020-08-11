<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class LoginSessions extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'id_session';
    protected $keyType = 'string';

    protected $fillable = [
        'id_session', 'id_user', 'id_employee', 'is_active'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];

    public function employee()
    {
        return $this->belongsTo('App\Employee', 'foreign_key');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'foreign_key');
    }
    
}
