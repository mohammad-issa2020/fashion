<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware'=>'check_guards:expert-api', 'prefix'=>'expert'], function(){
    Route::post('get-usage', 'ExpertController@index');  //just for example
    Route::post('logout', 'ExpertController@logout');
   //expert profile 
    Route::post('expert-profile', 'ExpertController@displayExpertProfile');
    //form edit form
    Route::post('edit-expert-profile', 'ExpertController@editExpertProfile');
    //save edit form
    Route::post('update-expert-profile', 'ExpertController@updateExpertProfile');
    //add fashion news
    Route::post('add-fashion-news', 'ExpertController@addFashionNews');
    
});

Route::group(['middleware'=>'api', 'prefix'=>'expert'], function(){
    Route::post('login', 'ExpertController@login') ;
    Route::post('register', 'ExpertController@register') ;
});