<?php

namespace frontend\components\helpers;

use Yii;

class BaseHelper
{
    public static function mergeWithCurrentParams($params)
    {
        $currentParams = Yii::$app->getRequest()->getQueryParams();
        if (!isset($params[0])) {
            $currentParams[0] = '/' . Yii::$app->controller->getRoute();
        }
        return array_merge($currentParams, $params);
    }
}
