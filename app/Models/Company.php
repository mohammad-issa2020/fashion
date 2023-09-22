<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Company extends Authenticatable implements JWTSubject
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'companies';
    protected $primaryKey='id';
    protected $fillable = [
        'name', 'email', 'password', 'location', 'major_category', 'image', 'num_follow'
    ];

    ########### relations ##############
   
    public function reply(){ //
        return $this->hasMany('App\Models\Answer');
    }

    public function comment(){  //
        return $this->hasMany('App\Models\Comment');
    }

    public function company_notification(){ //
        return $this->hasMany('App\Models\Company_notification');
    }

    public function answer(){ //
        return $this->hasMany('App\Models\Answer');
    }

    
    public function pieces(){ //
        return $this->hasMany('Piece');
    }

    public function follow(){ //
        return $this->hasMany('Follow');
    }

    public function form(){ //
        return $this->hasMany('Form');
    }

    public function star(){ //
        return $this->hasMany('Star');
    }

    public function fashion_news(){
        return $this->hasMany('App\Models\fashion_news');
    }

    ############################## end relations########################

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    } 
}
