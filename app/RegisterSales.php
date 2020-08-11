<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class RegisterSales extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'register_sales';
    protected $primaryKey = null;
    public $incrementing = false;
    protected $fillable = [
        'id_sales','id_sales_line'
    ];

    public function sales(){
        return $this->belongsTo('App\Sales','foreign_key');
    }

    public function salesLineItem(){
        return $this->belongsTo('App\SalesLineItem','foreign_key');
    }
}
