<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Owner extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $primaryKey = null;
    public $incrementing = false;
    protected $fillable = [
        'id_user','id_store'
    ];

    protected $hidden = [

    ];

    public function store(){
        return $this->belongsTo('App\Store','foreign_key');
    }

    public function user(){
        return $this->belongsTo('App\User','foreign_key');
    }
}
