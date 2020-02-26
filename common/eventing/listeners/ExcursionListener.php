<?php

namespace common\eventing\listeners;

use Yii;
use common\eventing\listeners\traits\SendMessageTrait;
use common\eventing\listeners\traits\CreateLinkTrait;
use common\eventing\events\ExcursionRejectedEvent;
use common\eventing\events\ExcursionAcceptedEvent;
use common\eventing\events\ExcursionCreatedByAdminEvent;
use common\eventing\events\ExcursionUpdatedByOwnerEvent;
use common\eventing\events\ExcursionCreatedEvent;
use common\models\Excursion;
use common\models\EmailTemplate;

class ExcursionListener
{
    use SendMessageTrait, CreateLinkTrait;

    public static function handleCreation(ExcursionCreatedEvent $event)
    {
        $excursionLink = Yii::$app->urlManagerBackend->createAbsoluteUrl([
                'excursion/update',
                'id' => $event->excursionId,
            ]);
        self::sendMessage(
            Yii::$app->params['supportEmail'],
            EmailTemplate::ALIAS_EXCURSION_CREATED_BY_USER,
            [
                '{{excursion_id}}'    => $event->excursionId,
                '{{excursion_link}}'  => self::createLink($excursionLink),
            ],
            'en'
        );
//         self::sendMessage(
//             Yii::$app->params['supportEmail'],
//             'Globeall. New excursion has been added',
//             <<<BODY
// New excursion has been added:
// ID {$event->excursionId}
// BODY
//             );
    }

    public static function handleReject(ExcursionRejectedEvent $event)
    {
        if ($excursion = Excursion::findOne($event->excursionId)) {
            $user = $excursion->user;
            $excursionLink = Yii::$app->urlManagerFrontend->createAbsoluteUrl([
                'office/update-excursion',
                'id' => $excursion->id,
            ]);
            self::sendMessage(
                $user->email,
                EmailTemplate::ALIAS_EXCURSION_REJECTED,
                [
                    '{{full_name}}'        => $user->full_name,
                    '{{excursion_id}}'     => $excursion->id,
                    '{{rejection_reason}}' => $excursion->rejection_reason,
                    '{{excursion_link}}'   => self::createLink($excursionLink),
                ],
                $excursion->user->interface_language
            );
//             self::sendMessage(
//                 $user->email,
//                 'Globeall. Your excursion has been rejected',
//                 <<<BODY
// Hello {$user->full_name},
// Your excursion has been rejected. Reason:
// {$excursion->rejection_reason}
// BODY
//             );
        }
    }

    public static function handleAccept(ExcursionAcceptedEvent $event)
    {
        if ($excursion = Excursion::findOne($event->excursionId)) {
            $user = $excursion->user;
            $excursionLink = Yii::$app->urlManagerFrontend->createAbsoluteUrl([
                'excursion/view',
                'excursion_id' => $excursion->id,
            ]);
            self::sendMessage(
                $user->email,
                EmailTemplate::ALIAS_EXCURSION_ACCEPTED,
                [
                    '{{full_name}}'        => $user->full_name,
                    '{{excursion_id}}'     => $excursion->id,
                    '{{excursion_link}}'   => self::createLink($excursionLink),
                ],
                $excursion->user->interface_language
            );
//             self::sendMessage(
//                 $user->email,
//                 'Globeall. Your excursion has been accepted',
//                 <<<BODY
// Hello {$user->full_name},
// Your excursion has been accepted
// BODY
//             );
        }
    }

    public static function handleCreatedByAdmin(ExcursionCreatedByAdminEvent $event)
    {
        // if ($excursion = Excursion::findOne($event->excursionId)) {
        //     $user = $excursion->user;
        //     self::sendMessage(
        //         $user->email,
        //         EmailTemplate::ALIAS_EXCURSION_ASSIGNED_TO_USER,
        //         [
        //             '{{full_name}}'        => $user->full_name,
        //             '{{excursion_id}}'     => $excursion->id,
        //         ]
        //     );
        // }
    }

    public static function handleUpdatedByOwner(ExcursionUpdatedByOwnerEvent $event)
    {
        if ($excursion = Excursion::findOne($event->excursionId)) {
            $excursionLink = Yii::$app->urlManagerBackend->createAbsoluteUrl([
                'excursion/update',
                'id' => $excursion->id,
            ]);
            $profileLink = Yii::$app->urlManagerBackend->createAbsoluteUrl([
                'user/update',
                'id' => $excursion->user->id,
            ]);
            self::sendMessage(
                Yii::$app->params['supportEmail'],
                EmailTemplate::ALIAS_EXCURSION_UPDATED_BY_OWNER,
                [
                    '{{excursion_id}}'     => $excursion->id,
                    '{{excursion_link}}'   => self::createLink($excursionLink),
                    '{{profile_link}}'     => self::createLink($profileLink),
                ],
                $excursion->user->interface_language
            );
//             self::sendMessage(
//                 Yii::$app->params['supportEmail'],
//                 'Globeall. Excursion has been updated',
//                 <<<BODY
// Excursion has been updated
// ID {$excursion->id}
// BODY
//             );
        }
    }
}
