<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class FileAttachAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/file-attach.js',
    ];
    public $depends = [
        'frontend\assets\AppAsset',
    ];
}
