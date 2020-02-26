<?php

namespace backend\controllers;

use Yii;
use backend\controllers\base\Controller;

class FileController extends Controller
{
    public function actionClear()
    {
        $request = Yii::$app->request;
        $modelClass = $request->post('modelClass');
        $modelId = $request->post('modelId');
        $modelAttribute = $request->post('modelAttribute');
        $modelClass::updateAll([$modelAttribute => null], ['id' => $modelId]);
    }
}
