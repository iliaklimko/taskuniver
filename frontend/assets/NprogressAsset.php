<?php

namespace frontend\assets;

use Yii;
use yii\web\AssetBundle;

class NprogressAsset extends AssetBundle
{
    public $sourcePath = '@bower/nprogress';

    public $js = [
        'nprogress.js',
    ];

    public function init()
    {
        $this->css[] = Yii::getAlias('@web/js/vendors/nprogress/nprogress.css');
    }
}
