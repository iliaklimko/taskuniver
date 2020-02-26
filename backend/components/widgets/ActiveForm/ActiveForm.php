<?php

namespace backend\components\widgets\ActiveForm;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\base\InvalidCallException;
use yii\bootstrap\ActiveForm as BaseActiveForm;

class ActiveForm extends BaseActiveForm
{
    public function run()
    {
        if (!empty($this->_fields)) {
            throw new InvalidCallException('Each beginField() should have a matching endField() call.');
        }

        $content = ob_get_clean();
        echo Html::beginForm($this->action, $this->method, $this->options);
        echo $content;

        if ($this->enableClientScript) {
            $id = $this->options['id'];
            $options = Json::htmlEncode($this->getClientOptions());
            $attributes = Json::htmlEncode($this->attributes);
            $view = $this->getView();
            ActiveFormAsset::register($view);
            $view->registerJs("jQuery('#$id').yiiActiveForm($attributes, $options);");
        }

        echo Html::endForm();
    }
}
