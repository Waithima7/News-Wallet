<?php
$controller = "UsersController@";
Route::get('/',$controller.'index');
Route::post('/',$controller.'storeLink');
Route::get('/list',$controller.'listLinks');