<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'seasons';
    protected $primaryKey='id';
    protected $fillable = [
        'name'
    ];

    ########### relations ##############
   
    public function pieces(){
        return $this->hasMany('App\Models\Piece');
    }
}
