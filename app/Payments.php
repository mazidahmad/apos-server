<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Payments extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $primaryKey = 'id_payment';
    protected $keyType = 'string';
    
    protected $fillable = [
        'id_sales','cash','change_amount'
    ];

    protected $hidden = [

    ];

    public function sales(){
        return $this->belongsTo('App\Sales','foreign_key');
    }
}
