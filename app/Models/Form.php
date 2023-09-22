<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'forms';
    protected $primaryKey='id';
    protected $fillable = [
       'company_id', 'season_id', 'average_rate'
    ];

    ########### relations ##############

    public function table_user(){
        return $this->hasMany('App\Models\Table_user');
    }

    public function company(){
        return $this->belongsTo('App\Models\Company', 'company_id', 'id');
    }
}

