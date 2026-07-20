<?php

// comment out the following two lines when deployed to production
define('YII_DEBUG', true);
define('YII_ENV', 'dev');

function wild_dump($ar)
{
    $ar['date'] = date_create();
    $content = print_r($ar, true);
    file_put_contents(__DIR__ . '/wild_logs.log', $content, FILE_APPEND);
}

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
