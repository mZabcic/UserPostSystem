<?php

use Illuminate\Http\Request;


Route::group(['middleware' => ['guest'],'prefix' => '/'], function() {
    Route::post('register', 'UserController@register');
    Route::post('login', 'UserController@authenticate');
    Route::get('', 'UserController@welcome');
});

Route::group(['middleware' => ['jwt.verify'],'prefix' => 'users'], function() {
    Route::get('/', 'UserController@getAll');
    Route::get('me', 'UserController@getAuthenticatedUser');
    Route::get('{id}', 'UserController@getById');
    Route::post('/', 'UserController@create');
    Route::delete('{id}', 'UserController@delete');
    Route::put('{id}', 'UserController@update');
});

Route::group(['middleware' => ['jwt.verify'],'prefix' => 'posts'], function() {
    Route::post('/', 'PostController@create');
});