<?php

include_once 'Http.php';
include_once 'Table.php';
echo "请输入一个格式为Y-m-d的日期：";
// 控制台stdin
$date = trim(fgets(STDIN));
// 为空则默认为当天
$date = empty($date) ? date('Y-m-d') : $date;

$dateTime = DateTime::createFromFormat('Y-m-d', $date);
if ($dateTime !== false && $dateTime->format('Y-m-d') !== $date) {
    exit('日期格式错误，请重新输入');
}

// 发起请求
$http = new Http();
$data = $http->send($date);

// 制作表格
$table = new Table();
$titleData = $table->getTable($data);
$table->printTable($titleData);







