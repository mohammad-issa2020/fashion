<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'collections';
    protected $primaryKey='id';
    protected $fillable = [
        'name'
    ];

    ########### relations ##############
   
    public function pieces_details(){
        return $this->hasMany('App\Models\Piece_details');
    }
}
