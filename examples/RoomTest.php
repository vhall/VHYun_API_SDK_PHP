<?php
use paas\Room;

include "../vendor/autoload.php";

$config = [
	'appId' => 'xxxx', // 控制台中获取
	'secretKey' => 'xxx', // 控制台中获取
	'show_request_url' => false, // 是否显示构造请求连接&参数 json console (请勿在生产环境打开)
	'show_request_data' => false, // 是否显示接口返回数据 json console (请勿在生产环境打开)
];

$roomObj = new Room($config);
$resultCreate = $roomObj->create();

$resultList = $roomObj->lists();

var_dump($resultCreate);
var_dump($resultList);






