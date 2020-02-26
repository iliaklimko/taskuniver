<?php

namespace console\controllers;

use Yii;
use yii\helpers\Console;
use yii\console\Controller;
use common\models\MainBenefit;

class MainBenefitController extends Controller
{
    public function actionInit()
    {
        $t1  = $this->createModel(MainBenefit::ALIAS_BLOCK_1_1);
        $t2  = $this->createModel(MainBenefit::ALIAS_BLOCK_1_2);
        $t3  = $this->createModel(MainBenefit::ALIAS_BLOCK_1_3);
        $t4  = $this->createModel(MainBenefit::ALIAS_BLOCK_2_1);
        $t5  = $this->createModel(MainBenefit::ALIAS_BLOCK_2_2);
        $t6  = $this->createModel(MainBenefit::ALIAS_BLOCK_2_3);

        if ($t1 && $t2 && $t3 && $t4 && $t5 && $t6) {
            $this->stdout(sprintf("Default Main benefits have been created.\n"), Console::FG_GREEN);

            return self::EXIT_CODE_NORMAL;
        }

        $this->stdout("Errors occurred.\n", Console::FG_RED);

        return self::EXIT_CODE_ERROR;
    }

    protected function createModel($alias)
    {
        $model = MainBenefit::findOne(['block' => $alias]);
        if (!$model) {
            $model = new MainBenefit([
                'block' => $alias,
            ]);
            return $model->save();
        }
        return true;
    }
}
