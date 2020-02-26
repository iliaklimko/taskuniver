<?php

namespace common\eventing\listeners\traits;

use yii\helpers\Html;

trait CreateLinkTrait
{
    protected static function createLink($link)
    {
        return Html::a(Html::encode($link), $link);
    }
}
