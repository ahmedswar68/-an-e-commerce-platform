<?php

Route::resource('categories', 'CategoriesController');
Route::resource('products', 'ProductController');
Route::resource('addresses', 'AddressController');
Route::resource('countries', 'CountryController');
Route::get('addresses/{address}/shipping', 'AddressShippingController@action');

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
