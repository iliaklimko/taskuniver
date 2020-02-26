<?php

use yii\helpers\Html;
use common\models\Excursion;

$price_status = Yii::$app->request->get('price_status') === null || Yii::$app->request->get('price_status') === ''
    ? 'null value'
    : (int) Yii::$app->request->get('price_status');

$options = array_map(function ($value) use ($price_status) {
    return [
        'value' => $value,
        'text' => Excursion::priceStatus()[$value],
        'selected' => $price_status === $value,
    ];
}, array_keys(Excursion::priceStatus()));
?>

<div class="input-wrap input-wrap--msg">
    <label><span><?= Yii::t('app', 'ExcursionsPage.filter.freeOrPaid') ?></span></label>
    <div class="select-wrap">
        <select class="fs" name="price_status" data-placeholder="<?= Yii::t('app', 'ExcursionsPage.filter.anyFreeOrPaid') ?>">
            <option value=""><?= Yii::t('app', 'ExcursionsPage.filter.anyFreeOrPaid') ?></option>
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
