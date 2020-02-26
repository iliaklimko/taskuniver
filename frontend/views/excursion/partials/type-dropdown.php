<?php

use yii\helpers\Html;

$options = array_map(function ($excursionType) {
    return [
        'value' => $excursionType->id,
        'text' => $excursionType->name,
        'selected' => Yii::$app->request->get('type') == $excursionType->id,
    ];
}, $excursionTypeList);
?>

<div class="input-wrap input-wrap--msg">
    <label><span><?= Yii::t('app', 'ExcursionsPage.filter.excursionType') ?></span></label>
    <div class="select-wrap">
        <select class="fs" name="type" data-placeholder="<?= Yii::t('app', 'ExcursionsPage.filter.anyExcursionType') ?>">
            <option value=""><?= Yii::t('app', 'ExcursionsPage.filter.anyExcursionType') ?></option>
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
