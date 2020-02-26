<?php

use yii\helpers\Html;

$options = array_map(function ($language) {
    return [
        'value' => $language->id,
        'text' => $language->name,
        'selected' => Yii::$app->request->get('language') == $language->id,
    ];
}, $languageList);
?>

<div class="input-wrap input-wrap--msg">
    <label><span><?= Yii::t('app', 'ExcursionsPage.filter.language') ?></span></label>
    <div class="select-wrap">
        <select class="fs" name="language" data-placeholder="<?= Yii::t('app', 'ExcursionsPage.filter.anyLanguage') ?>">
            <option value=""><?= Yii::t('app', 'ExcursionsPage.filter.anyLanguage') ?></option>
            <?php foreach ($options as $option): ?>
            <option value="<?= $option['value'] ?>"
                <?= $option['selected'] ? 'selected' : null ?>
            >
                <?= $option['text'] ?>
            </option>
            <?php endforeach; ?>
        </select>
        <span class="input-wrap__message"></span>
    </div>
</div>
