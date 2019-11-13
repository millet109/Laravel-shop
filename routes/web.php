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

//Route::get('products/{product}', 'ProductsController@show')->name('products.show');

Route::group(['middleware' => ['auth','verified']],function (){

    Route::get('user_addresses','UserAddressesController@index')->name('user_addresses.index');

    Route::get('user_addresses/create','UserAddressesController@create')->name('user_addresses.create');

    Route::post('user_addresses','UserAddressesController@store')->name('user_addresses.store');

    Route::get('user_addresses/{user_address}','UserAddressesController@edit')->name('user_addresses.edit');

    Route::put('user_addresses/{user_address}','UserAddressesController@update')->name('user_addresses.update');

    Route::delete('user_addresses/{user_address}','UserAddressesController@destroy')->name('user_addresses.destroy');

    Route::post('products/{product}/favorite', 'ProductsController@favor')->name('products.favor');

    Route::delete('products/{product}/favorite', 'ProductsController@disfavor')->name('products.disfavor');

    Route::get('products/favorites', 'ProductsController@favorites')->name('products.favorites');
});
/**
 * 初次添加好路由：Route::get('products/favorites', 'ProductsController@favorites')->name('products.favorites');
 * 提示页面不存在，原因是：和之前的 products/{product} 这个路由冲突了
 * Laravel 在匹配路由的时候会按定义的顺序依次查找，找到第一个匹配的路由就返回。所以当我们访问这个 URL 的时候会先匹配到商品详情页这个路由，然后把 favorites 当成商品 ID 去数据库查找，查不到对应的商品就抛出了不存在的异常
 * 所以将上面的 products/{product} 路由删除（目前是注释，为了区分），移动到下面
 */
Route::get('products/{product}', 'ProductsController@show')->name('products.show');