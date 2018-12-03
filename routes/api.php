<?php

use Illuminate\Http\Request;



Route::post('register', 'UserController@register');
Route::post('login', 'UserController@authenticate');

Route::group(['middleware' => ['jwt.verify'],'prefix' => 'users'], function() {
    Route::get('/', 'UserController@getAllUsers');
    Route::get('me', 'UserController@getAuthenticatedUser');
    Route::get('{id}', 'UserController@getUserById');
});