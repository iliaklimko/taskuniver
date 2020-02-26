<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AvatarChangeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/avatar-change.js',
    ];
    public $depends = [
        'frontend\assets\AppAsset',
    ];
}
