<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Outlet extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $primaryKey = 'id_outlet';
    protected $keyType = 'string';
    
    protected $fillable = [
        'id_outlet','id_store','name_outlet','address_outlet','phone_outlet','photo_store','is_active'
    ];

    protected $hidden = [

    ];

    public function store(){
        return $this->belongsTo('App\Store','foreign_key');
    }

    public function employee(){
        return $this->hasMany('App\Employee','foreign_key');
    }

    public function outletMenu(){
        return $this->hasMany('App\OutletMenu','foreign_key');
    }

    public function sales(){
        return $this->hasMany('App\Sales','foreign_key');
    }

    public function customMenu(){
        return $this->hasMany('App\CustomMenu','foreign_key');
    }
}
