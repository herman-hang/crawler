<?php

include_once 'Http.php';
include_once 'Table.php';
echo "请输入一个格式为Y-m-d的日期：";
// 控制台stdin
$date = trim(fgets(STDIN));

// 为空直接获取最新一条
if (empty($date)) {
    $uri = "loading=first";
} else {
    $dateTime = DateTime::createFromFormat('Y-m-d', $date);
    if ($dateTime !== false && $dateTime->format('Y-m-d') !== $date) {
        exit('日期格式错误，请重新输入');
    }
    $uri = "txtDate=$date";
}

// 发起请求
$http = new Http();
$data = $http->send($uri);

// 制作表格
$table = new Table();
$titleData = $table->getTable($data);
$table->printTable($titleData);







