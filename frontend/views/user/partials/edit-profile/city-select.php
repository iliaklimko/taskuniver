<?php

use yii\helpers\Html;

$userCityIds = array_map(function ($city) {
    return $city->id;
}, $model->cities);
$userCityIds = Yii::$app->request->isPost
    ? (Yii::$app->request->post($model->formName())['cityIds'] ?: [])
    : $userCityIds;
?>

<div class="input-wrap <?= $model->hasErrors('cityIds') ? 'input-wrap--error' : null ?>">
    <label><?= $model->getAttributeLabel('cityIds') ?>: <sup>*</sup></label>
    <div class="input-wrap__overflow">
        <input type="hidden" name="<?= $model->formName() ?>[cityIds]" value=""></input>
        <select multiple="multiple"
            class="multiple-select"
            name="<?= $model->formName() ?>[cityIds][]"
        >
            <?php foreach ($cityList as $city): ?>
            <option value="<?= $city->id ?>"
                <?= in_array($city->id, $userCityIds) ? 'selected' : '' ?>
            >
                <?= $city->name ?>
            </option>
            <?php endforeach; ?>
        </select>
        <?= Html::error($model, 'cityIds', ['tag' => 'span', 'class' => 'label-error']) ?>
        <div class="input-wrap__overflow-msg city-select">
            <?= Yii::t('app', 'GuideEditProfilePage.citiesHint') ?>
        </div>
    </div>
</div>
