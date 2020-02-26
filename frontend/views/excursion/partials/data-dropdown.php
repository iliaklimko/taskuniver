<?php

use yii\helpers\Html;
use common\models\Excursion;
use kartik\date\DatePicker;

?>

<div class="input-wrap input-wrap--msg">
    <label><span><?= Yii::t('app', 'ExcursionsPage.filter.ExcursionDate') ?></span></label>
    <div class="select-wrap">
        <?
        echo DatePicker::widget([
            'name' => 'dates',
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'value' => Yii::$app->request->get('dates'),
            'removeButton' => false,
            'options' => ['class' =>'jq-selectbox__select','placeholder' => Yii::t('app', 'ExcursionsPage.filter.anyExcursionDate')],
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-d',
                'startDate' => date('Y-m-d'),
            ]
        ]);?>
        <span class="input-wrap__message"></span>
    </div>
</div>
