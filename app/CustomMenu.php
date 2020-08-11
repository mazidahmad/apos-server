<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class CustomMenu extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'id_custom_menu';
    protected $keyType = 'string';

    protected $fillable = [
        'id_custom_menu', 'id_outlet', 'price', 'description'
    ];


    public function outlet()
    {
        return $this->belongsTo('App\Outlet', 'foreign_key');
    }

    public function salesLineItem(){
        return $this->hasMany('App\SalesLineItem','foreign_key');
    }
}
