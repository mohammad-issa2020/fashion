<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'replies';
    protected $primaryKey='id';
    protected $fillable = [
        'details', 'type', 'type_id', 'comment_id'
    ];

    ########### relations ##############
    public function admin(){
        return $this->belongsTo('App\Models\Admin', 'type_id', 'id');
    }

    public function table_user(){
        return $this->belongsTo('App\Models\Table_user', 'type_id', 'id');
    }

    public function company(){
        return $this->belongsTo('App\Models\Company', 'type_id', 'id');
    }

    public function expert(){
        return $this->belongsTo('App\Models\Expert', 'type_id', 'id');
    }

    public function comment(){
        return $this->belongsTo('App\Models\Comment', 'comment_id', 'id');
    }


}
