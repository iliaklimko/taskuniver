<?php

namespace console\controllers;

use Yii;
use yii\helpers\Console;
use yii\console\Controller;
use common\models\UserGroup;

class UserGroupController extends Controller
{
    public function actionInit()
    {
        $group1 = $this->createUserGroup(UserGroup::ALIAS_REGISTERED, 'Registered');
        $group2 = $this->createUserGroup(UserGroup::ALIAS_GUIDE, 'Guide');

        if ($group1 && $group2) {
            $this->stdout(sprintf("Default User Groups have been created.\n"), Console::FG_GREEN);

            return self::EXIT_CODE_NORMAL;
        }

        $this->stdout("Errors occurred.\n", Console::FG_RED);

        return self::EXIT_CODE_ERROR;
    }

    protected function createUserGroup($alias, $name)
    {
        $group = UserGroup::findOne(['alias' => $alias]);
        if (!$group) {
            $group = new UserGroup([
                'alias' => $alias,
                'name' => $name,
            ]);
            return $group->save();
        }
        return true;
    }
}
