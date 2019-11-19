<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Monolog\Logger;
use Yansongda\Pay\Pay;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * $this->app->singleton() 往服务容器中注入一个单例对象，
         * 第一次从容器中取对象时会调用回调函数来生成对应的对象并保存到容器中，之后再去取的时候直接将容器中的对象返回
         */

        //往服务容器中注入一个alipay的单例对象
        $this->app->singleton('alipay',function (){
            $config = config('pay.alipay');
            /**
             * notify_url 代表服务器端回调地址，return_url 代表前端回调地址
             * 回调地址必须是完整的带有域名的 URL，不可以是相对路径
             * 使用 route() 函数生成的 URL 默认就是带有域名的完整地址
             *
             * 如果是本地域名，支付宝服务器无法请求到我们的服务器端回调地址，
             * 使用http://requestbin.net/
             */
            $config['notify_url'] = 'http://requestbin.net/r/1jdd9t61';
            //$config['notify_url'] = route('payment.alipay.notify');
            $config['return_url'] = route('payment.alipay.return');
            // 判断当前项目运行环境是否为线上环境
            if (app()->environment() !== 'production') {
                $config['mode']         = 'dev';
                $config['log']['level'] = Logger::DEBUG;
            } else {
                $config['log']['level'] = Logger::WARNING;
            }
            // 调用 Yansongda\Pay 来创建一个支付宝支付对象
            return Pay::alipay($config);
        });

        $this->app->singleton('wechat_pay', function () {
            $config = config('pay.wechat');
            if (app()->environment() !== 'production') {
                $config['log']['level'] = Logger::DEBUG;
            } else {
                $config['log']['level'] = Logger::WARNING;
            }
            // 调用 Yansongda\Pay 来创建一个微信支付对象
            return Pay::wechat($config);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
    }
}
