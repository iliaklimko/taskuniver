<?php

namespace frontend\components\currency;

use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;

class CurrencySelector implements BootstrapInterface
{
    public $supportedCurrencies = [];

    public function bootstrap($app)
    {
        $preferredCurrency = isset($app->request->cookies['currency'])
            ? (string) $app->request->cookies['currency']
            : null;
        if (empty($preferredCurrency)) {
            $preferredCurrency = $this->getPreferredCurrency($this->supportedCurrencies);
        }
        $app->currency = $preferredCurrency;
    }

    protected function getPreferredCurrency(array $currencies = [])
    {
        if (empty($currencies)) {
            throw new InvalidConfigException('Property `supportedCurrencies` is not set.');
        }

        return reset($currencies);
    }
}
