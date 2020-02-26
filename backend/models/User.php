<?php

namespace backend\models;

use common\models\User as CommonUser;

class User extends CommonUser
{
    const SCENARIO_CREATE = 'scenario.create';

    public $plain_password;

    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [['user_group_id', 'full_name', 'phone', 'bio'], 'required'],
                [['cityIds', 'languageIds'], 'required'],
                ['plain_password', 'required', 'on' => self::SCENARIO_CREATE],
                ['plain_password', 'string', 'min' => User::MIN_PASS_LEN],
            ]
        );
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->setPassword($this->plain_password);
            return true;
        }
        return false;
    }
}
