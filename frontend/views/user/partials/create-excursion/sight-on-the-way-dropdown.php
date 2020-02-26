<?php

use yii\helpers\Html;

$sightIds = array_map(function ($sight) {
    return $sight->id;
}, $model->sights);
$sightIds = Yii::$app->request->isPost
    ? (Yii::$app->request->post($model->formName())['sightIds'] ?: [])
    : $sightIds;
?>

<div class="input-wrap <?= $model->hasErrors('sightIds') ? 'input-wrap--error' : null ?>">
    <label><?= Yii::t('app', 'UpdateExcursionPage.sights') ?>:</label>
    <div class="input-wrap__overflow">
        <input type="hidden" name="<?= $model->formName() ?>[sightIds]" value=""></input>
        <select multiple="multiple"
            class="multiple-select"
            name="<?= $model->formName() ?>[sightIds][]"
        >
            <?php foreach ($sightList as $sight): ?>
            <option value="<?= $sight->id ?>"
                <?= in_array($sight->id, $sightIds) ? 'selected' : '' ?>
            >
                <?= $sight->name ?>
            </option>
            <?php endforeach; ?>
        </select>
        <div class="input-wrap__overflow-msg">
            <?= Yii::t('app', 'UpdateExcursionPage.sightsHint') ?>
        </div>
        <?= Html::error($model, 'sightIds', ['tag' => 'span', 'class' => 'label-error']) ?>
    </div>
</div>
