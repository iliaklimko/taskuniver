<?php

namespace common\eventing\listeners\traits;

use Yii;

trait SendSmsTrait
{
    protected static function sendSms($phone, $text)
    {
        $sms = Yii::$app->sms;
        return $sms->send($phone, $text);
    }
}
