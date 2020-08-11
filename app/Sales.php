<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class Sales extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $primaryKey = 'id_sales';
    protected $keyType = 'string';
    
    protected $fillable = [
        'id_sales','id_outlet','id_employee','id_voucher','total_price','tax','customer_name','is_paid'
    ];

    protected $hidden = [

    ];

    public function payment(){
        return $this->hasOne('App\Payment','foreign_key');
    }

    public function registerSales(){
        return $this->hasMany('App\RegisterSales','foreign_key');
    }
}
