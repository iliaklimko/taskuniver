<?php

namespace frontend\components\url;

use Yii;
use yii\web\UrlRule;

class LocaleUrlRule extends UrlRule
{
    public function init()
    {
        if ($this->pattern !== null) {
            $this->pattern = '<locale:(ru|en)>/' . $this->pattern;
            // for subdomain it should be:
            // $this->pattern =  'http://<locale>.example.com/' . $this->pattern,
        }
        $this->defaults['locale'] = Yii::$app->language;
        parent::init();
    }
}
