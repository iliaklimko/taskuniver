<?php

namespace common\eventing\listeners;

use Yii;
use common\eventing\listeners\traits\SendMessageTrait;
use common\eventing\listeners\traits\CreateLinkTrait;
use common\eventing\events\UserCreatedEvent;
use common\eventing\events\UserPasswordChangedEvent;
use common\models\User;
use common\models\EmailTemplate;

class UserListener
{
    use SendMessageTrait, CreateLinkTrait;

    public static function handleCreation(UserCreatedEvent $event)
    {
        if ($user = User::findOne($event->userId)) {
            $confirmLink = Yii::$app->urlManager->createAbsoluteUrl([
                'site/signup-confirm',
                'token' => $user->signup_confirm_token,
                'locale' => $user->interface_language != 'ru' ? $user->interface_language : null,
            ]);
            self::sendMessage(
                $user->email,
                EmailTemplate::ALIAS_USER_SIGNUP,
                [
                    '{{login}}'        => $user->email,
                    '{{full_name}}'    => $user->full_name,
                    '{{confirm_link}}' => self::createLink($confirmLink),
                ],
                $user->interface_language
            );
//             self::sendMessage(
//                 $user->email,
//                 'Globeall. You have registered',
//                 <<<BODY
// Hello {$user->full_name},
// Follow the link below to confirm your account:
// {$confirmLink}
// BODY
//             );
        }
    }

    public static function handlePasswordChange(UserPasswordChangedEvent $event)
    {
        if ($user = User::findOne($event->userId)) {
            $resetLink = Yii::$app->urlManager->createAbsoluteUrl([
                'site/request-password-reset',
                'locale' => $user->interface_language != 'ru' ? $user->interface_language : null,
            ]);
            $profileLink = Yii::$app->urlManagerBackend->createAbsoluteUrl([
                'user/update',
                'id' => $user->id,
            ]);
            self::sendMessage(
                Yii::$app->params['supportEmail'],
                EmailTemplate::ALIAS_USER_PASSWORD_CHANGE,
                [
                    '{{full_name}}'    => $user->full_name,
                    '{{profile_link}}' => self::createLink($profileLink),
                    '{{reset_link}}'   => self::createLink($resetLink),
                ],
                'en'
            );
            self::sendMessage(
                $user->email,
                EmailTemplate::ALIAS_USER_PASSWORD_CHANGE_TO_HIMSELF,
                [
                    '{{full_name}}'    => $user->full_name,
                    '{{reset_link}}'   => self::createLink($resetLink),
                ],
                $user->interface_language
            );
//             self::sendMessage(
//                 $user->email,
//                 'Globeall. Your password has been changed',
//                 <<<BODY
// Hello {$user->full_name},
// Your password has been changed.
// Follow the link below to reset it:
// {$resetLink}
// BODY
//             );
        }
    }
}
