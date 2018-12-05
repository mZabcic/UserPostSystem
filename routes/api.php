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
    Route::get('/{id}/posts', 'UserController@getPostsByUserId');
});

Route::group(['middleware' => ['jwt.verify'],'prefix' => 'posts'], function() {
    Route::post('/', 'PostController@create');
    Route::get('/', 'PostController@getAll');
    Route::get('/{id}', 'PostController@getById');
    Route::delete('/{id}', 'PostController@delete');
    Route::get('/{id}/image', 'PostController@getImageById');
    Route::put('{id}', 'PostController@update');
    Route::post('{id}/image', 'PostController@updateImage');
});