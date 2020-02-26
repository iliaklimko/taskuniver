<?php

use yii\helpers\Html;

$userStartCityId = Yii::$app->request->isPost
    ? (Yii::$app->request->post($model->formName())['start_city_id'] ?: null)
    : $model->start_city_id;

$options = array_map(function ($city) use ($userStartCityId) {
    return [
        'value' => $city->id,
        'text' => $city->name,
        'selected' => $userStartCityId == $city->id,
    ];
}, $cityList);
?>

<div class="input-wrap <?= $model->hasErrors('start_city_id') ? 'input-wrap--error' : null ?>">
    <label><span><?= Yii::t('app', 'UpdateExcursionPage.startCity') ?>: <sup>*</sup></span></label>
    <div class="input-wrap__overflow">
        <input type="hidden" name="<?= $model->formName() ?>[start_city_id]" value="" />
        <select class="fs" name="<?= $model->formName() ?>[start_city_id]">
            <option value=""></option>
            <?php foreach ($options as $option): ?>
            <option value="<?= $option['value'] ?>"
                <?= $option['selected'] ? 'selected' : null ?>
            >
                <?= $option['text'] ?>
            </option>
            <?php endforeach; ?>
        </select>
        <div class="input-wrap__overflow-msg">
            <?= Yii::t('app', 'UpdateExcursionPage.startCityHint') ?>
        </div>
        <?= Html::error($model, 'start_city_id', ['tag' => 'span', 'class' => 'label-error']) ?>
    </div>
</div>
