<?php
// ensure we get report on all possible php errors
error_reporting(-1);

define('YII_ENABLE_ERROR_HANDLER', false);
define('YII_DEBUG', true);

require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
require_once(__DIR__ . '/compatibility.php');

Yii::setAlias('@yiiviet/tests/unit/n2w', __DIR__);
Yii::setAlias('@yiiviet/n2w', dirname(__DIR__) . '/src');
