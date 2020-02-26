<?php

use yii\helpers\Html;

$options = array_map(function ($city) {
    return [
        'value' => $city->id,
        'text' => $city->name,
        'selected' => Yii::$app->request->get('start_city') == $city->id,
    ];
}, $cityList);
?>

<div class="input-wrap input-wrap--msg">
    <label><span><?= Yii::t('app', 'ExcursionsPage.filter.startCity') ?></span></label>
    <div class="select-wrap">
        <select class="fs" name="start_city" data-placeholder="<?= Yii::t('app', 'ExcursionsPage.filter.anyStartCity') ?>">
            <option value=""><?= Yii::t('app', 'ExcursionsPage.filter.anyStartCity') ?></option>
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
