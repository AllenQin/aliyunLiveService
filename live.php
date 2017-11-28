<?php
include './vendor/autoload.php';
include "./AliyunLiveService.php";

$response = AliyunLiveService::getInstance()->getLiveMixConfig();
print_r($response);
