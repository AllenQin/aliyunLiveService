# 阿里云直播相关接口封装类

## 环境要求

- PHP 5.6+

## 安装依赖
```shell
	
	composer composer require 'allenqin/aliyun-openapi-php-sdk:v1.1.6'

```

## 使用

```php
include './vendor/autoload.php';
include "./AliyunLiveService.php";

$response = AliyunLiveService::getInstance()->getLiveMixConfig();
print_r($response);
```
## Authors && Contributors

- [AllenQin](https://github.com/AllenQin)
