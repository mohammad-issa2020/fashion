<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Piece extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'pieces';
    protected $primaryKey='id';
    protected $fillable = [
        'company_id', 'sub_category_id', 'season_id', 'usage_id', 'name', 'num_liked', 'price',
        'master_category_id', 'image'
    ];

    ########### relations ##############
   
    public function comment(){//
        return $this->hasMany('App\Models\Comment');
    }

    public function like(){//
        return $this->hasMany('App\Models\Like');
    }

    public function piece_details(){//
        return $this->hasMany('App\Models\Piece_details');
    }

    public function company(){//
        return $this->belongsTo('App\Models\Company', 'company_id', 'id');
    }

    public function sub_category(){//
        return $this->belongsTo('App\Models\Sub_category', 'sub_category_id', 'id');
    }

    public function season(){//
        return $this->belongsTo('App\Models\Season', 'season_id', 'id');
    }

    public function usage(){//
        return $this->belongsTo('App\Models\Usage', 'usage_id', 'id');
    }
}
