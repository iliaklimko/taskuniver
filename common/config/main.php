<?php

$main = [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'assetManager' => [
            'linkAssets' => true,
            'appendTimestamp' => true,
        ],
        'emailer' => [
            'class'      => 'common\components\email\Emailer',
            'htmlLayout' => 'raw-content-html',
        ],
        'formatter' => [
            'decimalSeparator'  => ',',
            'thousandSeparator' => '&nbsp;'
        ],
    ],
];

return array_merge($main, require('eventing.php'));
