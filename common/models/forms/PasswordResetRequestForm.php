<?php

namespace common\models\forms;

use Yii;
use yii\helpers\Html;
use yii\base\Model;
use common\models\User;
use common\models\EmailTemplate;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => '\common\models\User',
                // 'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => Yii::t('app', 'There is no user with such email')
            ],
            ['email', 'validateEmail'],
        ];
    }

    public function validateEmail($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = User::findOne([
                // 'status' => User::STATUS_ACTIVE,
                'email' => $this->email,
            ]);
            if ($user && $user->status == User::STATUS_INACTIVE) {
                $this->addError($attribute, Yii::t('app', 'Signup not confirmed'));
            }
        }
    }


    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
            // 'status' => User::STATUS_ACTIVE,
            'email' => $this->email,
        ]);

        if (!$user) {
            return false;
        }

        if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
            $user->generatePasswordResetToken();
            if (!$user->save()) {
                return false;
            }
        }

        $resetLink = $this->createResetLink($user);
        return $this->sendMessage(
            $this->email,
            EmailTemplate::ALIAS_USER_PASSWORD_RESET_REQUEST,
            [
                '{{full_name}}'    => $user->full_name,
                '{{reset_link}}'   => $resetLink,
            ]
        );

        // return Yii::$app
        //     ->mailer
        //     ->compose(
        //         ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
        //         ['user' => $user]
        //     )
        //     ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
        //     ->setTo($this->email)
        //     ->setSubject('Password reset for ' . Yii::$app->name)
        //     ->send();
    }

    protected function createResetLink($user)
    {
        $link = Yii::$app
            ->urlManager
            ->createAbsoluteUrl([
                'site/reset-password',
                'locale' => Yii::$app->language !== 'ru' ? Yii::$app->language : null,
                'token' => $user->password_reset_token
            ]);
        return Html::a(Html::encode($link), $link);
    }

    protected function sendMessage($to, $templateAlias, $params)
    {
        $template = EmailTemplate::findOne(['alias' => $templateAlias]);
        if (!$template) {
            return false;
        }
        $from = [Yii::$app->params['supportEmail'] => Yii::$app->name];
        $emailer = Yii::$app->emailer;
        $emailer->setTemplate($template);
        return $emailer->send($from, $to, $params);
    }
}
