<?php

Route::get('/', 'WelcomeController@index');

// User routes
Route::get('v1/users', 'UserController@index');
Route::get('v1/users/:id', 'UserController@show');
Route::get('v1/users/:id/withdrawal-histories', 'UserController@withdrawalHistory');
Route::get('v1/users/:id/balance-histories', 'UserController@balanceHistory');
Route::post('v1/users', 'UserController@store');
Route::post('v1/users/:id', 'UserController@update'); // It should be PUT method, but not working in testing with postman
Route::delete('v1/users/:id', 'UserController@destroy');

// Withdrawal routes
Route::get('v1/withdraw/:id', 'WithdrawalController@show');
Route::post('v1/withdraw', 'WithdrawalController@withdraw');

