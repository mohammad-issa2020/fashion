<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware'=>'check_guards:company-api', 'prefix'=>'company'], function(){
    Route::post('get-usage', 'CompanyController@index'); 
    //company profile 
    Route::post('company-profile', 'CompanyController@displayCompanyProfile');
    //form edit form
    Route::post('edit-company-profile', 'CompanyController@editCompanyProfile');
    //save edit form
    Route::post('update-company-profile', 'CompanyController@updateCompanyProfile');
    //add fashion news
    Route::post('add-company-fashion-news', 'CompanyController@addFashionNews');

    Route::post('add-pieces', 'CompanyController@addPieces');
    Route::post('logout', 'CompanyController@logout');
    //get sub master usage color sizeseason for add piece
    Route::post('get-usage', 'CompanyController@getUsage');
    Route::post('get-season', 'CompanyController@getSeasons');
    Route::post('get-sub-category', 'CompanyController@getSubCategories');
    Route::post('get-color', 'CompanyController@getColor');
    Route::post('get-size', 'CompanyController@getSize');
    Route::post('get-master-category', 'CompanyController@getMasterCategories');
    //add piece form
    Route::post('add-piece-form', 'CompanyController@addPiece');
    Route::post('display-my-pieces', 'CompanyController@displayPiece');

    
    

    
});

Route::group(['middleware'=>'api', 'prefix'=>'company'], function(){
    Route::post('login', 'CompanyController@login') ;
    Route::post('register', 'CompanyController@register') ;
});