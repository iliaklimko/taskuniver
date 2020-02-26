<?php

namespace frontend\components\payler;

use common\models\Excursion;
use Yii;
use yii\base\Object;
use common\models\Order;
use common\models\User as User;

class PaylerComponent extends Object
{
    const TYPE = "OneStep";

    public $test;
    public $key;
    public $password;

    private $refundPassword = 'XY8KUBfHZn';
    private $base_url;
    private $url;

    public function init()
    {
        $host = ($this->test ? "sandbox" : "secure");
        $this->base_url = "https://" . $host . ".payler.com/gapi/";
    }

    /**
     * @desc Отправка POST-запроса при помощи curl.
     *
     * @param $data Массив отправляемых данных
     * @result Ассоциативный массив возвращаемых данных
     */
    public function CurlSendPost($data)
    {
        $headers = array(
            'Content-type: application/x-www-form-urlencoded',
            'Cache-Control: no-cache',
            'charset="utf-8"',
	    );
        $data = http_build_query($data, '', '&');
        $options = array (
            CURLOPT_URL => $this->url,
            CURLOPT_POST => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 45,
            CURLOPT_VERBOSE => 0,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        );
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $json = curl_exec($ch);
        if ($json == false) {
            die ('Curl error: ' . curl_error($ch) . '<br>');
        }
        //Преобразуем JSON в ассоциативный массив
        $result = json_decode($json, TRUE);
	    curl_close($ch);
    	return $result;
    }

    /**
    * @desc Обмен данными с Gate API Payler
    *
    * @param array $data Массив отправляемых данных
    * @param string $method Метод API
    * @result Ассоциативный массив возвращаемых данных
    */
    public function POSTtoGateAPI($data, $method)
    {
        $this->url = $this->base_url.$method;
        $result = $this->CurlSendPost($data);
        return $result;
    }

    public function startPay(Order $order)
    {
        $data = $this->loadData($order);
        $session_data = $this->POSTtoGateAPI($data, "StartSession");
        if(isset($session_data['session_id'])) {
            $session_id = $session_data['session_id'];
            return [
                'response' => [
                    'url'        => $this->base_url."Pay",
                    'session_id' => $session_id,
                ]
            ];
        }
        throw new \RuntimeException('Response has no `session_id` key');
    }

    private function loadData(Order $order)
    {
        $excursion = $order->excursion;
        $order_id = $order->code;
        $amount   = $order->price;
        /** Получаем процент за оплату экскурсии */
        if (!empty($_REQUEST['prepayment_percent'])) {
            $amount = ceil(round(round($amount / 100, 2) * ((int)$_REQUEST['prepayment_percent'] / 100), 2)) * 100;
        }

        $product  = Yii::t('app', 'Payment for {title}, {quantity}, {date}', [
            'title'    => $excursion->title,
            'quantity' => $order->quantity,
            'date'     => $order->date,
        ]);
        $total    = 1;
        $currency = $order->currency;
        $data = [
            'key'      => $this->key,
            'type'     => self::TYPE,
            'order_id' => $order_id,
            'currency' => $currency,
            'amount'   => ceil($amount),
            'product'  => $product,
            'total'    => $total,
            'lang'     => Yii::$app->language,
        ];
        return $data;
    }

    public function refundPay(Order $order) {
        $data = [
            'key'      => $this->key,
            'password' => $this->refundPassword,
            'order_id' => $order->code,
            'amount'   => $order->price / 100,
        ];
        $this->POSTtoGateAPI($data, "Refund");
    }

}
