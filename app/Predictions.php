<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Predictions extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'periode','id_outlet_menu','name_menu','wma','mape'
    ];

    protected $hidden = [

    ];

    public function outletMenu(){
        return $this->belongsTo('App\OutletMenu','foreign_key');
    }
}
