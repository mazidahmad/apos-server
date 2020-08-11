<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class PredictionLines extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'prediction_lines';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'periode','start_periode_date','end_periode_date','id_outlet_menu','sales_qty','wma_1','error_1','presentation_error_1'
        ,'wma_2','error_2','presentation_error_2','wma_3','error_3','presentation_error_3','wma_4','error_4','presentation_error_4'
    ];

    public function outletMenu(){
        return $this->belongsTo('App\OutletMenu','foreign_key');
    }
}
