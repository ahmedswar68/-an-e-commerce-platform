<?php

Route::resource('categories', 'CategoriesController');
Route::resource('products', 'ProductController');

Route::group(['prefix' => 'auth'], function () {
  Route::post('register', 'Auth\RegisterController@action');
});
