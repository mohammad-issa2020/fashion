<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Expert extends Authenticatable implements JWTSubject
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'experts';
    protected $primaryKey='id';
    protected $fillable = [
        'name', 'email', 'password', 'date_of_birth', 'gender', 'details', 'image'
    ];

    ########### relations ##############
    public function expert_notification(){
        return $this->hasMany('App\Models\Expert_notification');
    }

    public function reply(){
        return $this->hasMany('App\Models\Reply');
    }

    public function comment(){
        return $this->hasMany('App\Models\Comment');
    }

    public function answer(){
        return $this->hasMany('App\Models\Answer');
    }

    public function fashion_news(){
        return $this->hasMany('App\Models\fashion_news');
    }

    ####################### end relations ####################
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
