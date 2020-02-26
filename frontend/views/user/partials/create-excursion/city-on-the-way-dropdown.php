<?php

use yii\helpers\Html;

$cityIds = array_map(function ($city) {
    return $city->id;
}, $model->cities);
$cityIds = Yii::$app->request->isPost
    ? (Yii::$app->request->post($model->formName())['cityIds'] ?: [])
    : $cityIds;
?>

<div class="input-wrap">
    <label><?= Yii::t('app', 'UpdateExcursionPage.citiesOnWay') ?>:</label>
    <div class="input-wrap__overflow">
        <input type="hidden" name="<?= $model->formName() ?>[cityIds]" value=""></input>
        <select multiple="multiple"
            class="multiple-select"
            name="<?= $model->formName() ?>[cityIds][]"
        >
            <?php foreach ($cityList as $city): ?>
            <option value="<?= $city->id ?>"
                <?= in_array($city->id, $cityIds) ? 'selected' : '' ?>
            >
                <?= $city->name ?>
            </option>
            <?php endforeach; ?>
        </select>
        <div class="input-wrap__overflow-msg">
            <?= Yii::t('app', 'UpdateExcursionPage.citiesOnWayHint') ?>
        </div>
        <?= Html::error($model, 'cityIds', ['tag' => 'span', 'class' => 'label-error']) ?>
    </div>
</div>
