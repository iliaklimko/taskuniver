<?php

namespace frontend\models;

use Yii;
use common\models\UserGroup;
use common\eventing\UserEvents;
use common\eventing\events\UserCreatedEvent;

/**
 * Signup form
 */
class SignupForm extends User
{
    public $plain_password;
    public $agree = false;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                ['email', 'validateEmail'],
                ['email', 'unique', 'message' => Yii::t('app', 'Email has already been taken')],
                ['agree', 'boolean'],
                ['agree', 'compare', 'compareValue' => true, 'message' => Yii::t('app', 'You must agree')],
                ['plain_password', 'required'],
                ['plain_password', 'string', 'min' => User::MIN_PASS_LEN],
            ]
        );
    }

    public function validateEmail($attribute, $params)
    {
        $user = User::findOne(['email' => $this->email]);
        if ($user && $user->status == User::STATUS_INACTIVE) {
            $this->addError($attribute, Yii::t('app', 'GuideSignupPage.notConfirmed'));
            Yii::$app->trigger(
                UserEvents::USER_CREATED,
                new UserCreatedEvent([
                    'userId' => $user->id,
                ])
            );
        }
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($group = UserGroup::findOne(['alias' => UserGroup::ALIAS_GUIDE])) {
            $this->user_group_id = $group->id;
        }

        if (!$this->validate() || !$this->agree) {
            return null;
        }

        $this->status = self::STATUS_INACTIVE;
        $this->setPassword($this->plain_password);
        $this->generateAuthKey();
        $this->generateSignupConfirmToken();

        $saved = $this->save();
        if ($saved) {
            Yii::$app->trigger(
                UserEvents::USER_CREATED,
                new UserCreatedEvent([
                    'userId' => $this->id,
                ])
            );
        }

        return $saved ? $this : null;
    }
}
