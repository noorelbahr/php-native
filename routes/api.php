<?php

Route::get('/', 'WelcomeController@index');

// User routes
Route::get('v1/users', 'UserController@index');
Route::get('v1/users/:id', 'UserController@show');
Route::post('v1/users', 'UserController@store');
Route::put('v1/users/:id', 'UserController@update');
Route::delete('v1/users/:id', 'UserController@destroy');

//Route::exec();
