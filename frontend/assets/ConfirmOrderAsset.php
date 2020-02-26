<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class ConfirmOrderAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/confirm-order.js',
    ];
    public $depends = [
        'frontend\assets\AppAsset',
    ];
}
