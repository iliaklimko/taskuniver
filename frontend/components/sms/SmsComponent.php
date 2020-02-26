<?php

namespace frontend\components\sms;

use Yii;
use yii\base\Object;

class SmsComponent extends Object
{
    const METHOD_SEND = 'send';
    const STATUS_MESSAGE_ACCEPTED           = 'accepted';
    const STATUS_MESSAGE_NOT_ENOUGH_BALANCE = 'not enough balance';

    public $login;
    public $password;

    private $base_url;
    private $url;

    public function init()
    {
        $this->base_url = 'http://api.prostor-sms.ru/messages/v2/';
    }

    public function send($phone, $text)
    {
        $data = [
            'phone'    => $phone,
            'text'     => $text,
            'login'    => $this->login,
            'password' => $this->password,
        ];
        $result = $this->sendToGate($data, self::METHOD_SEND);
        $result = $this->parseSendResult($result);
        list($status, $smsId) = $result;
        if ($status == self::STATUS_MESSAGE_ACCEPTED) {
            return $smsId;
        }
        return [
            'error' => $status,
        ];
    }

    private function parseSendResult($result)
    {
        return explode(';', $result);
    }

    private function sendToGate($data, $method)
    {
        $data = http_build_query($data, '', '&');
        $this->url = $this->base_url . $method . '/?' . $data;
        $result = $this->curlSend($data);
        return $result;
    }

    private function curlSend($data)
    {
        $options = [
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => 1,
        ];
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        if ($result == false) {
            die ('Curl error: ' . curl_error($ch) . '<br>');
        }
        curl_close($ch);
        return $result;
    }
}
