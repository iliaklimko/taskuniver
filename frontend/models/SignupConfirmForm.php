<?php

namespace frontend\models;

use yii\base\Model;
use yii\base\InvalidParamException;
use common\models\User;

class SignupConfirmForm extends Model
{
    /**
     * @var \common\models\User
     */
    private $_user;


    /**
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Signup confirm token cannot be blank.');
        }
        $this->_user = User::findBySignupConfirmToken($token);
        if (!$this->_user) {
            throw new InvalidParamException('Wrong signup confirm token.');
        }
        parent::__construct($config);
    }

    /**
     * @return boolean
     */
    public function setUserActive()
    {
        $user = $this->_user;
        $user->status = User::STATUS_ACTIVE;
        $user->signup_confirm_token = null;

        return $user->save(false);
    }

    public function getUser()
    {
        return $this->_user;
    }
}
