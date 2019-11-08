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

Route::get('/','PagesController@root')->name('root');
//需要让 Laravel 启用与邮箱验证相关的路由（验证邮箱页面、重发验证邮件页面等）
Auth::routes(['verify' => true]);

