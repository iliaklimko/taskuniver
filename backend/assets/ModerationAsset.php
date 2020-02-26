<?php

namespace backend\assets;

use yii\web\AssetBundle;

class ModerationAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/moderation.js',
        'js/custom.js'
    ];
    public $depends = [
        'backend\assets\AppAsset',
    ];
}
