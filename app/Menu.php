<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Menu extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'id_menu';
    protected $keyType = 'string';

    protected $fillable = [
        'id_menu', 'id_store', 'name_menu', 'description', 'category',
        'photo_menu','is_active'
    ];


    public function store()
    {
        return $this->belongsTo('App\Store', 'foreign_key');
    }

    public function outletMenu(){
        return $this->hasMany('App\OutletMenu','foreign_key');
    }
}
