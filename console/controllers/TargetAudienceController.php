<?php

namespace console\controllers;

use Yii;
use yii\helpers\Console;
use yii\console\Controller;
use common\models\TargetAudience;

class TargetAudienceController extends Controller
{
    public function actionInit()
    {
        $audience1 = $this->createTargetAudience(TargetAudience::ALIAS_HEAR);
        $audience2 = $this->createTargetAudience(TargetAudience::ALIAS_SEE);
        $audience3 = $this->createTargetAudience(TargetAudience::ALIAS_WHEELCHAIR);
        $audience4 = $this->createTargetAudience(TargetAudience::ALIAS_MOTHER);
        $audience5 = $this->createTargetAudience(TargetAudience::ALIAS_ALL);

        if ($audience1 && $audience2 && $audience3 && $audience4 && $audience5) {
            $this->stdout(sprintf("Default Target Audiences have been created.\n"), Console::FG_GREEN);

            return self::EXIT_CODE_NORMAL;
        }

        $this->stdout("Errors occurred.\n", Console::FG_RED);

        return self::EXIT_CODE_ERROR;
    }

    protected function createTargetAudience($alias)
    {
        $audience = TargetAudience::findOne(['alias' => $alias]);
        if (!$audience) {
            $audience = new TargetAudience([
                'alias' => $alias,
            ]);
            $audience->scenario = 'fake_scenario';
            return $audience->save();
        }
        return true;
    }
}
