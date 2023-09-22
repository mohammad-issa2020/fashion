<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware'=>'check_guards:admin-api', 'prefix'=>'admin'], function(){
    Route::post('get-usage', 'AdminController@index')->name('dashboard');  //just for example
    Route::post('logout', 'AdminController@logout');
});

Route::group(['middleware'=>'api', 'prefix'=>'admin'], function(){
    Route::post('login', 'AdminController@login') ;
    Route::post('register', 'AdminController@register') ;
});