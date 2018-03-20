# cdn pusher for laravel 

这个包可以非常简单的将静态文件上传到 CDN 上

# 安装
`composer require ty666/cdn-pusher`

将以下数组添加到 `config/app.php` 中 (laravel5.5+ 跳过此步骤)
```
'providers' => array(
     //...
     Ty666\CdnPusher\CdnPusherServiceProvider::class,
),
```

# 配置
发布配置文件
`php artisan vendor:publish --provider="Ty666\CdnPusher\CdnPusherServiceProvider"`
配置文件保存在 `config/cdn.php` 中

添加以下配置到 `.env` 文件中
```
USE_CDN=true
FILESYSTEM_CLOUD=qiniu
```
# 使用
`php artisan cdn:push`

`php artisan cdn:push --rule=without_vendor`
