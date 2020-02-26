<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'name' => 'Globe4all',
    'language' => 'ru', // en|ru
    'basePath' => dirname(__DIR__),
    'homeUrl' => '/',
    'bootstrap' => [
        'log',
        'currencySelector',
    ],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'baseUrl' => '',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'loginUrl' => ['main-page/index', '#' => 'guide-enter'],
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'error-page/error',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => require('routes.php'),
        ],
        'urlManagerBackend' => [
            'class' => 'yii\web\urlManager',
            'baseUrl' => '/admin',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
                'yii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    // 'sourceLanguage' => 'en',
                    'basePath' => '@frontend/messages'
                ],
            ],
        ],
        'currencySelector' => [
            'class' => 'frontend\components\currency\CurrencySelector',
            'supportedCurrencies' => ['EUR', 'RUB'],
        ],
        'currencyConverter' => [
            'class' => 'frontend\components\currency\CurrencyConverter',
        ],
        'payler' => [
            'class'    => 'frontend\components\payler\PaylerComponent',
            'test'     => $params['payler']['test'],
            'key'      => $params['payler']['key'],
            'password' => $params['payler']['password'],
        ],
        'sms' => [
            'class'    => 'frontend\components\sms\SmsComponent',
            'login'    => $params['sms']['login'],
            'password' => $params['sms']['password'],
        ],
    ],
    'modules' => [
        'sitemap' => require(__DIR__ . '/sitemap.php'),
    ],
    'params' => $params,
];
