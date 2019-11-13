# Laravel-shop
基于Laravel 6.x版本开发的单商户电商系统。
# 1.安装
- 通过 git clone 命令进行安装，好处是无需生成APP_KEY
- 直接下载zip包解压缩配置使用，需要在命令行执行```php artisan key:generate```生成APP_KEY，将会自动配置在.env文件中
- 安装后请执行```composer install```命令安装扩展包。在这之前，如果你没有修改过镜像源，建议修改为国内阿里镜源地址保证 composer 安装加速。
```php
composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/
```
# 2.配置
- 克隆项目后复制```.env.example```文件，命名为```.env```文件
- 在你本地创建好数据库以后，配置好数据库连接，执行```php artisan migrate```命令执行数据迁移，详细内容请查阅文档。并且在以后的开发过程中强烈建议使用数据库迁移和填充对数据库进行版本控制。
如果你在执行该命令的过程中报错：Syntax error or access violation: 1071 Specified key was too long; max key length is 1000 bytes (SQL: alter table `users` add unique `users_email_unique`(`email`))
这个就是你的版本太低了，如果你不愿意解决版本问题。请在：```app\Providers\AppServiceProvider.php```文件的```boot```方法中添加：
```php
 public function boot()
    {
        Schema::defaultStringLength(191);
    }

```

# 3.功能包括:
- 用户中心
- 收货地址
- 电商管理后台
- 权限管理
- 商品管理
- 商品 SKU
- 购物车模块
- 订单模块
- 支付模块（支付宝、微信支付）
- 商品评价
- 商品收藏
- 订单退款流程
- 优惠券模块

技术知识点包括 Laravel 中事务操作（Transaction）、支付接口调试、订单流水号生成、预加载与延迟预加载、事件和监听器、调试邮件发送、Service 模式、自定义验证器等。
