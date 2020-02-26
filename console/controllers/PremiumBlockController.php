<?php

namespace console\controllers;

use Yii;
use yii\helpers\Console;
use yii\console\Controller;
use common\models\PremiumBlock;

class PremiumBlockController extends Controller
{
    public function actionInit()
    {
        $t1  = $this->createModel(PremiumBlock::ALIAS_BLOCK_1_1);
        $t2  = $this->createModel(PremiumBlock::ALIAS_BLOCK_1_2);
        $t3  = $this->createModel(PremiumBlock::ALIAS_BLOCK_1_3);
        $t4  = $this->createModel(PremiumBlock::ALIAS_BLOCK_1_4);
        $t5  = $this->createModel(PremiumBlock::ALIAS_BLOCK_2_1);
        $t6  = $this->createModel(PremiumBlock::ALIAS_BLOCK_2_2);
        $t7  = $this->createModel(PremiumBlock::ALIAS_BLOCK_2_3);
        $t8  = $this->createModel(PremiumBlock::ALIAS_BLOCK_2_4);
        $t9  = $this->createModel(PremiumBlock::ALIAS_BLOCK_2_5);

        if ($t1 && $t2 && $t3 && $t4 && $t5 && $t6 && $t7 && $t8 && $t9) {
            $this->stdout(sprintf("Default Premium blocks have been created.\n"), Console::FG_GREEN);

            return self::EXIT_CODE_NORMAL;
        }

        $this->stdout("Errors occurred.\n", Console::FG_RED);

        return self::EXIT_CODE_ERROR;
    }

    protected function createModel($alias)
    {
        $model = PremiumBlock::findOne(['alias' => $alias]);
        if (!$model) {
            $model = new PremiumBlock([
                'alias' => $alias,
            ]);
            $model->scenario = 'fake_scenario';
            return $model->save();
        }
        return true;
    }
}
