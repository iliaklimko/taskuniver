<?php

use yii\helpers\Html;
use common\models\Excursion;

$duration = Yii::$app->request->isPost
    ? (Yii::$app->request->post($model->formName())['duration'] ?: null)
    : $model->duration;

$options = array_map(function ($value) use ($duration) {
    return [
        'value' => $value,
        'text' => Excursion::getDurationList()[$value],
        'selected' => $duration == $value,
    ];
}, array_keys(Excursion::getDurationList()));
?>

<div class="input-wrap <?= $model->hasErrors('duration') ? 'input-wrap--error' : null ?>">
    <label><span><?= Yii::t('app', 'UpdateExcursionPage.duration') ?>: <sup>*</sup></span></label>
    <div class="select-wrap input-wrap__overflow">
        <input type="hidden" name="<?= $model->formName() ?>[duration]" value="">
        <select class="fs" name="<?= $model->formName() ?>[duration]">
            <option value=""></option>
            <?php foreach ($options as $option): ?>
            <option value="<?= $option['value'] ?>"
                <?= $option['selected'] ? 'selected' : null ?>
            >
                <?= $option['text'] ?>
            </option>
            <?php endforeach; ?>
        </select>
        <?= Html::error($model, 'duration', ['tag' => 'span', 'class' => 'label-error']) ?>
    </div>
</div>
