<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usage extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'usages';
    protected $primaryKey='id';
    protected $fillable = [
        'name'
    ];

    ########### relations ##############
    public function star(){
        return $this->hasMany('App\Models\Star');
    }

    public function pieces(){
        return $this->hasMany('App\Models\Piece');
    }
}
