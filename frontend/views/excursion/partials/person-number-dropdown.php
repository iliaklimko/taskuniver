<?php

use yii\helpers\Html;
use common\models\Excursion;

$options = array_map(function ($value) {
    return [
        'value' => $value,
        'text' => Excursion::getPersonNumberList()[$value],
        'selected' => Yii::$app->request->get('person_number') == $value,
    ];
}, array_keys(Excursion::getPersonNumberList()));
?>

<div class="input-wrap input-wrap--msg">
    <label><span><?= Yii::t('app', 'ExcursionsPage.filter.personNumber') ?></span></label>
    <div class="select-wrap">
        <select class="fs" name="person_number" data-placeholder="<?= Yii::t('app', 'ExcursionsPage.filter.anyPersonNumber') ?>">
            <option value=""><?= Yii::t('app', 'ExcursionsPage.filter.anyPersonNumber') ?></option>
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
