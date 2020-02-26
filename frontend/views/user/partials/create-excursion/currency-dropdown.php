<?php

use yii\helpers\Html;

$currencyId = Yii::$app->request->isPost
    ? (Yii::$app->request->post($model->formName())['currency_id'] ?: null)
    : $model->currency_id;

$options = array_map(function ($currency) use ($currencyId) {
    return [
        'value' => $currency->id,
        'text' => $currency->code,
        'selected' => $currencyId == $currency->id,
    ];
}, $currencyList);
?>

<div class="input-wrap <?= $model->hasErrors('currency_id') ? 'input-wrap--error' : null ?>">
    <label><span><?= Yii::t('app', 'Excursion.currency') ?>: <sup>*</sup></span></label>
    <div class="input-wrap__overflow">
        <input type="hidden" name="<?= $model->formName() ?>[currency_id]" value="" />
        <select class="fs" name="<?= $model->formName() ?>[currency_id]">
            <option value=""></option>
            <?php foreach ($options as $option): ?>
            <option value="<?= $option['value'] ?>"
                <?= $option['selected'] ? 'selected' : null ?>
            >
                <?= $option['text'] ?>
            </option>
            <?php endforeach; ?>
        </select>
        <!-- <div class="input-wrap__overflow-msg">
            Валюта
        </div> -->
        <?= Html::error($model, 'currency_id', ['tag' => 'span', 'class' => 'label-error']) ?>
    </div>
</div>
