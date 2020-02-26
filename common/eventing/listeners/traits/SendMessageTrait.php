<?php

namespace common\eventing\listeners\traits;

use Yii;
use common\models\EmailTemplate;

trait SendMessageTrait
{
    protected static function sendMessage($to, $templateAlias, $params, $langCode = 'ru')
    {
        $template = EmailTemplate::findOne(['alias' => $templateAlias]);
        if (!$template) {
            return false;
        }
        $template->setLanguage($langCode);
        $from = [Yii::$app->params['supportEmail'] => Yii::$app->name];
        $emailer = Yii::$app->emailer;
        $emailer->setTemplate($template);
        return $emailer->send($from, $to, $params);
    }
}
