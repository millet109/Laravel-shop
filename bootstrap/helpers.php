<?php
/**
 * Created by PhpStorm.
 * User: 0375
 * Date: 2019/11/7
 * Time: 15:54
 */
use Illuminate\Support\Facades\Route;

/**
 * 测试辅助函数
 * @return string
 */
function test_helper()
{
    return 'OK';
}

/**
 * 将当前请求的路由名称转换为 CSS 类名称，允许我们针对某个页面做页面样式定制
 * @return mixed
 */
function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}