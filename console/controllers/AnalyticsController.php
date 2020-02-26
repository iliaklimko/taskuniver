<?php

namespace console\controllers;

use Yii;
use yii\helpers\Console;
use yii\console\Controller;
use common\models\Analytics;

class AnalyticsController extends Controller
{
    public function actionInit()
    {
        $t  = $this->createModel(Analytics::ALIAS_BASE);

        if ($t) {
            $this->stdout(sprintf("Default Analytics have been created.\n"), Console::FG_GREEN);

            return self::EXIT_CODE_NORMAL;
        }

        $this->stdout("Errors occurred.\n", Console::FG_RED);

        return self::EXIT_CODE_ERROR;
    }

    protected function createModel($alias)
    {
        $model = Analytics::findOne(['alias' => $alias]);
        if (!$model) {
            $model = new Analytics([
                'alias' => $alias,
            ]);
            return $model->save();
        }
        return true;
    }
}
