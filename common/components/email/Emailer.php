<?php

namespace common\components\email;

use Yii;
use yii\base\Object;

class Emailer extends Object
{
    public $htmlLayout;

    protected $template;

    public function setTemplate(EmailTemplateInterface $template)
    {
        $this->template = $template;
    }

    public function send($from, $to, array $params = [])
    {
        $subject = $this->template->getSubject();
        $body    = $this->render($this->template->getBody(), $params);
        return $this->getMailer()
            ->compose(
                ['html'    => $this->htmlLayout],
                ['content' => $body]
            )
            ->setFrom($from)
            ->setTo($to)
            ->setSubject($subject)
            ->send();
    }

    protected function getMailer()
    {
        return Yii::$app->mailer;
    }

    protected function render($templateString, $params)
    {
        $result = $templateString;
        foreach ($params as $key => $value) {
            $result = str_replace($key, $value, $result);
        }
        return $result;
    }
}
