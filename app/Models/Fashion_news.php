<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fashion_news extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'fashion_news';
    protected $primaryKey='id';
    protected $fillable = [
        'type_id','type', 'title', 'details'
    ];

    ########### relations ##############
    public function admin(){
        return $this->belongsTo('App\Models\Admin', 'type_id', 'id');
    }

    public function expert(){
        return $this->belongsTo('App\Models\Expert', 'type_id', 'id');
    }

    public function company(){
        return $this->belongsTo('App\Models\Company', 'type_id', 'id');
    }
}
