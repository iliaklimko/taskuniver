<?php

use yii\helpers\Html;

$themeIds = array_map(function ($theme) {
    return $theme->id;
}, $model->themes);
$themeIds = Yii::$app->request->isPost
    ? (Yii::$app->request->post($model->formName())['themeIds'] ?: [])
    : $themeIds;
?>

<div class="input-wrap <?= $model->hasErrors('themeIds') ? 'input-wrap--error' : null ?>">
    <label><?= Yii::t('app', 'ExcursionsPage.filter.excursionTheme') ?>: <sup>*</sup></label>
    <div class="input-wrap__overflow">
        <input type="hidden" name="<?= $model->formName() ?>[themeIds]" value=""></input>
        <select multiple="multiple"
            class="multiple-select"
            name="<?= $model->formName() ?>[themeIds][]"
        >
            <?php foreach ($excursionThemeList as $theme): ?>
            <option value="<?= $theme->id ?>"
                <?= in_array($theme->id, $themeIds) ? 'selected' : '' ?>
            >
                <?= $theme->name ?>
            </option>
            <?php endforeach; ?>
        </select>
        <div class="input-wrap__overflow-msg">
            <?= Yii::t('app', 'UpdateExcursionPage.excursionThemeHint') ?>
        </div>
        <?= Html::error($model, 'themeIds', ['tag' => 'span', 'class' => 'label-error']) ?>
    </div>
</div>
