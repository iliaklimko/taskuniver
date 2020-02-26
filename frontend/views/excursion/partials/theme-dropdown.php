<?php

use yii\helpers\Html;

$options = array_map(function ($excursionTheme) {
    return [
        'value' => $excursionTheme->id,
        'text' => $excursionTheme->name,
        'selected' => Yii::$app->request->get('theme') == $excursionTheme->id,
    ];
}, $excursionThemeList);
?>

<div class="input-wrap input-wrap--msg">
    <label><span><?= Yii::t('app', 'ExcursionsPage.filter.excursionTheme') ?></span></label>
    <div class="select-wrap">
        <select class="fs" name="theme" data-placeholder="<?= Yii::t('app', 'ExcursionsPage.filter.anyExcursionTheme') ?>">
            <option value=""><?= Yii::t('app', 'ExcursionsPage.filter.anyExcursionTheme') ?></option>
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
