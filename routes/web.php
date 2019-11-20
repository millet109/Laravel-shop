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

    Route::post('cart', 'CartController@add')->name('cart.add');

    Route::get('cart', 'CartController@index')->name('cart.index');

    Route::delete('cart/{sku}','CartController@remove')->name('cart.remove');

    Route::post('orders', 'OrdersController@store')->name('orders.store');

    Route::get('orders', 'OrdersController@index')->name('orders.index');

    Route::get('orders/{order}', 'OrdersController@show')->name('orders.show');

    Route::get('payment/{order}/alipay', 'PaymentController@payByAlipay')->name('payment.alipay');

    Route::get('payment/alipay/return', 'PaymentController@alipayReturn')->name('payment.alipay.return');

    Route::post('orders/{order}/received','OrdersController@received')->name('orders.received');

    Route::get('orders/{order}/review', 'OrdersController@review')->name('orders.review.show');

    Route::post('orders/{order}/review', 'OrdersController@sendReview')->name('orders.review.store');
});
/**
 * 初次添加好路由：Route::get('products/favorites', 'ProductsController@favorites')->name('products.favorites');
 * 提示页面不存在，原因是：和之前的 products/{product} 这个路由冲突了
 * Laravel 在匹配路由的时候会按定义的顺序依次查找，找到第一个匹配的路由就返回。所以当我们访问这个 URL 的时候会先匹配到商品详情页这个路由，然后把 favorites 当成商品 ID 去数据库查找，查不到对应的商品就抛出了不存在的异常
 * 所以将上面的 products/{product} 路由删除（目前是注释，为了区分），移动到下面
 */
Route::get('products/{product}', 'ProductsController@show')->name('products.show');

//服务器端回调的路由不能放到带有 auth 中间件的路由组中，因为支付宝的服务器请求不会带有认证信息
Route::post('payment/alipay/notify', 'PaymentController@alipayNotify')->name('payment.alipay.notify');









/**
 * 支付宝测试路由
 * 无需理会，配置沙箱的时候自己可以自行使用该路由测试沙箱配置是否完善：yourUrl.com/alipay
 */
Route::get('alipay', function() {
    return app('alipay')->web([
        'out_trade_no' => time(),
        'total_amount' => '1',
        'subject' => 'test subject - 测试',
    ]);
});