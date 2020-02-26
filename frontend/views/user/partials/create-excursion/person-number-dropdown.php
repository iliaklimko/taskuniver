<?php

use yii\helpers\Html;
use common\models\Excursion;

$personNumber = Yii::$app->request->isPost
    ? (Yii::$app->request->post($model->formName())['person_number'] ?: null)
    : $model->person_number;

$options = array_map(function ($value) use ($personNumber) {
    return [
        'value' => $value,
        'text' => Excursion::getPersonNumberList()[$value],
        'selected' => $personNumber == $value,
    ];
}, array_keys(Excursion::getPersonNumberList()));
?>

<div class="input-wrap <?= $model->hasErrors('person_number') ? 'input-wrap--error' : null ?>">
    <label><span><?= Yii::t('app', 'UpdateExcursionPage.personNumber') ?>: <sup>*</sup></span></label>
    <div class="select-wrap input-wrap__overflow">
        <input type="hidden" name="<?= $model->formName() ?>[person_number]" value="">
        <select class="fs" name="<?= $model->formName() ?>[person_number]">
            <option value=""></option>
            <?php foreach ($options as $option): ?>
            <option value="<?= $option['value'] ?>"
                <?= $option['selected'] ? 'selected' : null ?>
            >
                <?= $option['text'] ?>
            </option>
            <?php endforeach; ?>
        </select>
        <?= Html::error($model, 'person_number', ['tag' => 'span', 'class' => 'label-error']) ?>
    </div>
</div>
