<?php

namespace backend\models\forms;

use Yii;
use common\models\forms\LoginForm as BaseLoginForm;
use common\models\Admin;

/**
 * Backend login form for admin
 */
class LoginForm extends BaseLoginForm
{
    /**
     * Finds admin by [[username]] or [[email]]
     *
     * @return Admin|null
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Admin::findByUsernameOrEmail($this->username);
        }

        return $this->_user;
    }
}
