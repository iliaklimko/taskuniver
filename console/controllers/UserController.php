<?php

namespace console\controllers;

use Yii;
use yii\helpers\Console;
use yii\console\Controller;
use common\models\forms\UserWithPlainPasswordForm;
use common\models\UserGroup;

class UserController extends Controller
{
    /**
     * @var string email
     */
    public $email;

    /**
     * @var string plain password
     */
    public $password;

    /**
     * @inheritdoc
     */
    public function options($action)
    {
        return array_merge(parent::options($action), [
            'email', 'password',
        ]);
    }

    public function actionCreate()
    {
        $user = $this->createUser($this->email, $this->password);

        if ($user) {
            $this->stdout(sprintf("User %s has been created.\n", $user->email), Console::FG_GREEN);

            return self::EXIT_CODE_NORMAL;
        }

        $this->stdout("Errors occurred.\n", Console::FG_RED);

        return self::EXIT_CODE_ERROR;
    }

    protected function createUser($email, $password)
    {
        $group = UserGroup::findOne(['alias' => UserGroup::ALIAS_REGISTERED]);
        if (!$group) {
            $group = new UserGroup([
                'alias' => UserGroup::ALIAS_REGISTERED,
                'name' => 'Registered',
            ]);
            if (!$group->save()) {
                return null;
            }
        }
        $model = new UserWithPlainPasswordForm([
            'console' => true,
            'email' => $email,
            'password' => $password,
            'group_id' => $group->id,
        ]);
        $user = $model->create(true);

        return $user;
    }
}
