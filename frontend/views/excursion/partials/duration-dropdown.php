<?php

use yii\helpers\Html;
use common\models\Excursion;

$options = array_map(function ($value) {
    return [
        'value' => $value,
        'text' => Excursion::getDurationList()[$value],
        'selected' => Yii::$app->request->get('duration') == $value,
    ];
}, array_keys(Excursion::getDurationList()));
?>

<div class="input-wrap input-wrap--msg">
    <label><span><?= Yii::t('app', 'ExcursionsPage.filter.duration') ?></span></label>
    <div class="select-wrap">
        <select class="fs" name="duration" data-placeholder="<?= Yii::t('app', 'ExcursionsPage.filter.anyDuration') ?>">
            <option value=""><?= Yii::t('app', 'ExcursionsPage.filter.anyDuration') ?></option>
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
