<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Store extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $primaryKey = 'id_store';
    protected $keyType = 'string';
    
    protected $fillable = [
        'id_store','name_store','logo_store'
    ];

    protected $hidden = [

    ];

    public function store(){
        return $this->hasMany('App\Store','foreign_key');
    }

    public function owner(){
        return $this->hasOne('App\Owner','foreign_key');
    }

    public function menu(){
        return $this->hasMany('App\Menu','foreign_key');
    }
}
