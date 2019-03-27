<?php

Route::resource('categories', 'CategoriesController');
Route::resource('products', 'ProductController');
Route::resource('cart', 'Cart\CartController', [
  'parameters' => [
    'cart' => 'productVariation'
  ]
]);

Route::group(['prefix' => 'auth'], function () {
  Route::post('login', 'Auth\LoginController@action');
  Route::post('register', 'Auth\RegisterController@action');
  Route::get('me', 'Auth\MeController@action');
});
