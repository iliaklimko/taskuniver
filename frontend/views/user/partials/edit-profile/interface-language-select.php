<?php

use yii\helpers\Html;

$langId = Yii::$app->request->isPost
    ? (Yii::$app->request->post($model->formName())['interface_language'] ?: null)
    : $model->interface_language;

// $options = array_combine(
//     Yii::$app->params['languagesAvailable'],
//     array_map(function ($lang) {return Yii::t('app', "Interface $lang");}, Yii::$app->params['languagesAvailable'])
// );
$options = array_map(function ($lang) use ($langId) {
    return [
        'value' => $lang,
        'text' => Yii::t('app', "Interface $lang"),
        'selected' => $lang == $langId,
    ];
}, Yii::$app->params['languagesAvailable']);
?>

<div class="input-wrap <?= $model->hasErrors('interface_language') ? 'input-wrap--error' : null ?>">
    <label><span><?= Yii::t('app', 'User.interfaceLanguage') ?>: <sup>*</sup></span></label>
    <div class="input-wrap__overflow">
        <input type="hidden" name="<?= $model->formName() ?>[interface_language]" value="" />
        <select class="fs" name="<?= $model->formName() ?>[interface_language]" id="user-interface_language">
            <option value=""></option>
            <?php foreach ($options as $option): ?>
            <option value="<?= $option['value'] ?>"
                <?= $option['selected'] ? 'selected' : null ?>
            >
                <?= $option['text'] ?>
            </option>
            <?php endforeach; ?>
        </select>
        <!-- <div class="input-wrap__overflow-msg">
            Валюта
        </div> -->
        <?= Html::error($model, 'interface_language', ['tag' => 'span', 'class' => 'label-error']) ?>
    </div>
</div>
