<?php
use paas\Document;

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






