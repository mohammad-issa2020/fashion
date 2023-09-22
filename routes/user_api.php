<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware'=>'check_guards:user-api', 'prefix'=>'user'], function(){
    Route::post('get-usage', 'TableUserController@index');
    //user profile 
    Route::post('user-profile', 'TableUserController@displayUserProfile');
    //form edit form
    Route::post('edit-user-profile', 'TableUserController@editUserProfile');
    //save edit form
    Route::post('update-user-profile', 'TableUserController@updateUserProfile');
    //add question form
    Route::post('add-question', 'TableUserController@addQuestion'); 
    Route::post('logout', 'TableUserController@logout');
});

Route::group(['middleware'=>'api', 'prefix'=>'user'], function(){
    Route::post('login', 'TableUserController@login') ;
    Route::post('register', 'TableUserController@register') ;
});