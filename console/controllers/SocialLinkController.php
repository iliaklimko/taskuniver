<?php

namespace console\controllers;

use Yii;
use yii\helpers\Console;
use yii\console\Controller;
use common\models\SocialLink;

class SocialLinkController extends Controller
{
    public function actionInit()
    {
        $link1 = $this->createSocialLink(SocialLink::ALIAS_VKONTAKTE);
        $link2 = $this->createSocialLink(SocialLink::ALIAS_FACEBOOK);
        $link3 = $this->createSocialLink(SocialLink::ALIAS_INSTAGRAM);
        $link4 = $this->createSocialLink(SocialLink::ALIAS_TWITTER);

        if ($link1 && $link2 && $link3 && $link4) {
            $this->stdout(sprintf("Default Social Links have been created.\n"), Console::FG_GREEN);

            return self::EXIT_CODE_NORMAL;
        }

        $this->stdout("Errors occurred.\n", Console::FG_RED);

        return self::EXIT_CODE_ERROR;
    }

    protected function createSocialLink($alias)
    {
        $link = SocialLink::findOne(['alias' => $alias]);
        if (!$link) {
            $link = new SocialLink([
                'alias' => $alias,
            ]);
            $link->scenario = 'fake_scenario';
            return $link->save();
        }
        return true;
    }
}
