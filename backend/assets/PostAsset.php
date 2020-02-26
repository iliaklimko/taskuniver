<?php

namespace backend\assets;

use yii\web\AssetBundle;

class PostAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/post.js'
    ];
    public $depends = [
        'backend\assets\AppAsset',
    ];
}
