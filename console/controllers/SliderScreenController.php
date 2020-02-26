<?php

namespace console\controllers;

use Yii;
use yii\helpers\Console;
use yii\console\Controller;
use common\models\SliderScreen;

class SliderScreenController extends Controller
{
    public function actionInit()
    {
        $t1  = $this->createModel(SliderScreen::ALIAS_01);
        $t2  = $this->createModel(SliderScreen::ALIAS_02);
        $t3  = $this->createModel(SliderScreen::ALIAS_03);
        $t4  = $this->createModel(SliderScreen::ALIAS_04);

        if ($t1 && $t2 && $t3 && $t4) {
            $this->stdout(sprintf("Default SliderScreens have been created.\n"), Console::FG_GREEN);

            return self::EXIT_CODE_NORMAL;
        }

        $this->stdout("Errors occurred.\n", Console::FG_RED);

        return self::EXIT_CODE_ERROR;
    }

    protected function createModel($alias)
    {
        $model = SliderScreen::findOne(['alias' => $alias]);
        if (!$model) {
            $model = new SliderScreen([
                'alias' => $alias,
            ]);
            return $model->save();
        }
        return true;
    }
}
