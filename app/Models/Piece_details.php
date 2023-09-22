<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Piece_details extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'piece_details';
    protected $primaryKey='id';
    protected $fillable = [
        'pieces_id', 'size_id', 'color_id', 'collection_id','image'
    ];

    ########### relations ##############
   
    public function pieces(){
        return $this->belongsTo('App\Models\Piece', 'pieces_id', 'id');
    }

    public function collection(){
        return $this->belongsTo('App\Models\Collection', 'collection_id', 'id');
    }
    public function size(){
        return $this->hasMany('App\Models\Size');
    }
    public function color(){
        return $this->hasMany('App\Models\Color');
    }
}
