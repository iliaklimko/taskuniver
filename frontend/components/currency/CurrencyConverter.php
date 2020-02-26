<?php

namespace frontend\components\currency;

use Yii;
use yii\base\Object;
use common\models\Currency;

class CurrencyConverter extends Object
{
    public $to;

    public function init()
    {
        $this->to = Yii::$app->currency;
    }

    public function convert($from, $amount = 1)
    {
        $to = $this->to;
        $currencyTo = $this->getCurrencyByCode($to);
        $currencyFrom = $this->getCurrencyByCode($from);

        if ($currencyFrom->id == $currencyTo->id) {
            return $amount;
        }

        return $amount
            * ($currencyFrom->amount     * $currencyTo->amount_cnt)
            / ($currencyFrom->amount_cnt * $currencyTo->amount);
    }

    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    protected function getCurrencyByCode($code)
    {
        return Currency::findOne(['code' => $code]);
    }
}
