<?php

use yii\helpers\Html;

$userLanguageIds = array_map(function ($language) {
    return $language->id;
}, $model->languages);
$userLanguageIds = Yii::$app->request->isPost
    ? (Yii::$app->request->post($model->formName())['languageIds'] ?: [])
    : $userLanguageIds;
?>

<div class="input-wrap <?= $model->hasErrors('languageIds') ? 'input-wrap--error' : null ?>">
    <label><?= $model->getAttributeLabel('languageIds') ?>: <sup>*</sup></label>
    <div class="input-wrap__overflow">
        <input type="hidden" name="<?= $model->formName() ?>[languageIds]" value=""></input>
        <select multiple="multiple"
            class="multiple-select"
            name="<?= $model->formName() ?>[languageIds][]"
        >
            <?php foreach ($languageList as $language): ?>
            <option value="<?= $language->id ?>"
                <?= in_array($language->id, $userLanguageIds) ? 'selected' : '' ?>
            >
                <?= $language->name ?>
            </option>
            <?php endforeach; ?>
        </select>
        <?= Html::error($model, 'languageIds', ['tag' => 'span', 'class' => 'label-error']) ?>
        <div class="input-wrap__overflow-msg language-select">
            <?= Yii::t('app', 'GuideEditProfilePage.languagesHint') ?>
        </div>
    </div>
</div>
