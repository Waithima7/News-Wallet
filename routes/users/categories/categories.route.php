<?php
$controller = "CategoriesController@";
Route::get('/',$controller.'index');
Route::post('/',$controller.'storeCategory');
Route::get('/list',$controller.'listCategories');
