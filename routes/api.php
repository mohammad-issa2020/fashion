<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware'=>'api'], function(){
Route::post('login', 'Auth\LoginController@login'); 
});
