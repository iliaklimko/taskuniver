<?php

namespace backend\controllers\base;

use Yii;
use yii\web\Controller as BaseController;
use yii\filters\AccessControl;

class Controller extends BaseController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
}