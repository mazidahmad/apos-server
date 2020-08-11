<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class OutletMenu extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'outlet_menus';
    protected $primaryKey = 'id_outlet_menu';
    protected $keyType = 'string';

    protected $fillable = [
        'id_outlet_menu', 'id_menu', 'id_outlet', 'cog','price', 'is_stock', 
        'stock'
        
    ];


    public function menu()
    {
        return $this->belongsTo('App\Menu', 'foreign_key');
    }

    public function outlet()
    {
        return $this->belongsTo('App\Outlet', 'foreign_key');
    }
}
