<?php

namespace frontend\models;

use Yii;
use common\eventing\UserEvents;
use common\eventing\events\UserPasswordChangedEvent;

class ProfileForm extends User
{
    public $plain_password_new;

    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                ['plain_password_new', 'string', 'min' => User::MIN_PASS_LEN],
                [
                    'email',
                    'unique',
                    'targetClass' => 'common\models\User',
                    'filter' => function ($query) {
                        $query->andWhere(['<>', 'id', $this->id]);
                    },
                ],
            ]
        );
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'plain_password_new' => 'Новый пароль',
        ]);
    }

    public function updateProfile()
    {
        if (!$this->validate()) {
            return false;
        }

        if ($this->plain_password_new) {
            $this->setPassword($this->plain_password_new);
        }

        $saved = $this->save(false);
        if ($saved && $this->plain_password_new) {
            Yii::$app->trigger(
                UserEvents::PASSWORD_CHANGED,
                new UserPasswordChangedEvent([
                    'userId' => $this->id,
                ])
            );
        }

        return $saved;
    }
}
