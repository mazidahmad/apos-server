<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class SalesLineItem extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'id_sales_line';
    protected $keyType = 'string';

    protected $fillable = [
        'id_sales_line', 'id_outlet_menu', 'id_custom_menu', 'quantity', 'discount',
        'subtotal_price','created_at','updated_at'
    ];


    public function outletMenu()
    {
        return $this->belongsTo('App\OutletMenu', 'foreign_key');
    }

    public function customMenu(){
        return $this->belongsTo('App\CustomMenu','foreign_key');
    }

    public function registerSales(){
        return $this->hasMany('App\RegisterSales','foreign_key');
    }
}
