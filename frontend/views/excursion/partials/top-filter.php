<?php

use yii\helpers\Html;

$values = [
    'current_price-asc'  => 'current_price-asc',
    'current_price-desc' => 'current_price-desc',
    'duration-asc'       => 'duration-asc',
    'duration-desc'      => 'duration-desc',
];

$firstOptionKey = Yii::$app->request->get('order_by') ?: 'current_price-asc';
$firstOption = [
    'value' => $values[$firstOptionKey],
    'text' => Yii::t('app', 'ExcursionsPage.first').' '.Yii::t('app', $values[$firstOptionKey]),
];
unset($values[$firstOptionKey]);

$options = array_map(function ($value) {
    return [
        'value' => $value,
        'text' => Yii::t('app', $value),
    ];
}, $values);
?>

<div class="top-filter">
    <div class="top-filter__select">
    <?= Html::beginForm(
        [
            'excursion/index',
            'locale'          => Yii::$app->request->get('locale'),
            'target_audience' => Yii::$app->request->get('target_audience'),
            'type'            => Yii::$app->request->get('type'),
            'theme'           => Yii::$app->request->get('theme'),
            'language'        => Yii::$app->request->get('language'),
            'duration'        => Yii::$app->request->get('duration'),
            'person_number'   => Yii::$app->request->get('person_number'),
            'start_city'      => Yii::$app->request->get('start_city'),
            'price_status'    => Yii::$app->request->get('price_status'),
        ],
        'get',
        ['id' => 'top-filter-form']
    ) ?>
        <select class="fs" name="order_by">
            <option value="<?= $firstOption['value'] ?>"
                selected
            >
                <?= $firstOption['text'] ?>
            </option>
            <?php foreach ($options as $option): ?>
            <option value="<?= $option['value'] ?>"
            >
                <?= $option['text'] ?>
            </option>
            <?php endforeach; ?>
        </select>
    <?= Html::endForm() ?>
    </div>
</div>
