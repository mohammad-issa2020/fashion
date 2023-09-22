<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Color extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'color';
    protected $primaryKey='id';
    protected $fillable = [
        'name'
    ];

    ########### relations ##############
    public function pieceDetails(){
        return $this->belongsTo('App\Models\Piece_details', 'color_id', 'id');
    }
}
