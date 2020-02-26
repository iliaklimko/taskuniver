<?php

namespace console\controllers;

use Yii;
use yii\helpers\Console;
use yii\console\Controller;
use common\models\forms\AdminWithPlainPasswordForm;
use common\models\AdminGroup;

class AdminController extends Controller
{
    /**
     * @var string username
     */
    public $username;

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
            'username', 'email', 'password',
        ]);
    }

    public function actionCreate()
    {
        $user = $this->createSuperAdmin($this->username, $this->email, $this->password);

        if ($user) {
            $this->stdout(sprintf("User %s has been created.\n", $user->username), Console::FG_GREEN);

            return self::EXIT_CODE_NORMAL;
        }

        $this->stdout("Errors occurred.\n", Console::FG_RED);

        return self::EXIT_CODE_ERROR;
    }

    protected function createSuperAdmin($username, $email, $password)
    {
        $group = AdminGroup::findOne(['name' => 'SuperAdmin']);
        if (!$group) {
            $group = new AdminGroup([
                'name' => 'SuperAdmin',
            ]);
            if (!$group->save()) {
                return null;
            }
        }
        $model = new AdminWithPlainPasswordForm([
            'console' => true,
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'group_id' => $group->id,
        ]);
        $user = $model->create(true);

        return $user;
    }
}
