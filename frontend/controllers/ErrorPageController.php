<?php

namespace frontend\controllers;

use Yii;

class ErrorPageController extends BaseController
{
    public $layout = 'error-page';

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
}
