<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::redirect('/', '/products')->name('root');
//需要让 Laravel 启用与邮箱验证相关的路由（验证邮箱页面、重发验证邮件页面等）
Auth::routes(['verify' => true]);

Route::get('products', 'ProductsController@index')->name('products.index');

Route::get('products/{product}', 'ProductsController@show')->name('products.show');

Route::group(['middleware' => ['auth','verified']],function (){

    Route::get('user_addresses','UserAddressesController@index')->name('user_addresses.index');

    Route::get('user_addresses/create','UserAddressesController@create')->name('user_addresses.create');

    Route::post('user_addresses','UserAddressesController@store')->name('user_addresses.store');

    Route::get('user_addresses/{user_address}','UserAddressesController@edit')->name('user_addresses.edit');

    Route::put('user_addresses/{user_address}','UserAddressesController@update')->name('user_addresses.update');

    Route::delete('user_addresses/{user_address}','UserAddressesController@destroy')->name('user_addresses.destroy');
});