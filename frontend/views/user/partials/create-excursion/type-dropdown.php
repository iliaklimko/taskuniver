<?php

use yii\helpers\Html;

$typeIds = array_map(function ($type) {
    return $type->id;
}, $model->types);
$typeIds = Yii::$app->request->isPost
    ? (Yii::$app->request->post($model->formName())['typeIds'] ?: [])
    : $typeIds;
?>

<div class="input-wrap <?= $model->hasErrors('typeIds') ? 'input-wrap--error' : null ?>">
    <label><?= Yii::t('app', 'UpdateExcursionPage.excursionType') ?>: <sup>*</sup></label>
    <div class="input-wrap__overflow">
        <input type="hidden" name="<?= $model->formName() ?>[typeIds]" value=""></input>
        <select multiple="multiple"
            class="multiple-select"
            name="<?= $model->formName() ?>[typeIds][]"
        >
            <?php foreach ($excursionTypeList as $type): ?>
            <option value="<?= $type->id ?>"
                <?= in_array($type->id, $typeIds) ? 'selected' : '' ?>
            >
                <?= $type->name ?>
            </option>
            <?php endforeach; ?>
        </select>
        <div class="input-wrap__overflow-msg">
            <?= Yii::t('app', 'UpdateExcursionPage.excursionTypeHint') ?>
        </div>
        <?= Html::error($model, 'typeIds', ['tag' => 'span', 'class' => 'label-error']) ?>
    </div>
</div>
