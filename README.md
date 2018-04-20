# VhallYun SDK for PHP
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)
[![Latest Stable Version](https://img.shields.io/packagist/v/vhall/web_sdk_php.svg)](https://packagist.org/packages/vhall/web_sdk)

## 安装

* 通过composer，这是推荐的方式，可以使用composer.json 声明依赖，或者运行下面的命令。SDK 包已经放到这里 [`vhall/paas_sdk`][install-packagist] 。
```bash
$ composer require vhall/web_sdk
```
* 直接下载安装，SDK 没有依赖其他第三方库，但需要参照 composer的autoloader，增加一个自己的autoloader程序。

## 运行环境

| Vhall PaaS SDK版本 | PHP 版本 |
|:--------------------:|:---------------------------:|
|          1.0         |  cURL extension,   5.6,7.0 |

## 使用方法

### 上传
1 使用composer形式
```php
use Paas\Room;
use Paas\Document;

include "../vendor/autoload.php";

$config = [
	'appId' => 'xxxx', // 控制台中获取
	'secretKey' => 'xxx', // 控制台中获取
	'show_request_url' => false, // 是否显示构造请求连接&参数 json console (请勿在生产环境打开)
	'show_request_data' => false, // 是否显示接口返回数据 json console (请勿在生产环境打开)
];

// 实例化直播对象
$roomObj = new Room($config);

// 创建房间
$resultCreate = $roomObj->create();

// 获取房间列表
$resultList = $roomObj->lists();

// 实例化文档对象
$roomObj = new Document($config);

// 文档参数填写
$params = [
	// 文档要写绝对路径
    'document' => __DIR__. "/test.pptx"
];

// 创建文档
$resultCreate = $roomObj->create($params);

```

2 直接引入使用
```php
use paas\Document;
// 直接引入SDK中的的autoload文件
include "../autoload.php";

$config = [
	'appId' => '342a10bb',
	'secretKey' => '7b887d97f11cb1f7c6cb890fbecf0367',
	'show_request_url' => false, // 是否显示构造请求连接&参数 json console (请勿在生产环境打开)
	'show_request_data' => false, // 是否显示接口返回数据 json console (请勿在生产环境打开)
];

$roomObj = new Document($config);

$params = [
    'document' => __DIR__. "/test.pptx"
];

$resultCreate = $roomObj->create($params);

var_dump($resultCreate);
```

## 常见问题

- 使用原生PHP异常处理错误，请使用catch(Exception $e) 进行捕获
- API 的使用 demo 可以参考 (https://github.com/vhall/VHYun_API_SDK_PHP/examples)。
- SDK不需要时刻保持最新版本,无说明所有API都可以使用SDK调用


## 联系我们

- 如果需要帮助，请提交工单（直接向 yan.gao@vhall.com 发送邮件）
- 更详细的文档，见[官方文档站](http://www.vhallyun.com/document/detail/index?project_id=40&doc_id=952)
- 如果发现了bug， 欢迎提交 [issue](https://github.com/vhall/VHYun_API_SDK_PHP/issues)
- 如果有功能需求，欢迎提交 [issue](https://github.com/vhall/VHYun_API_SDK_PHP/issues)

## 代码许可

The MIT License (MIT).详情见 [License文件](https://github.com/vhall/VHYun_API_SDK_PHP/blob/master/LICENSE).

[packagist]: http://packagist.org
[install-packagist]: https://packagist.org/packages/vhall/paas_sdk
