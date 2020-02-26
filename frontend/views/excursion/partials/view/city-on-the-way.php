<?php

$cities = array_map(function ($city) {
    return $city->name;
}, $model->cities);
?>

<?php if (count($cities) > 0): ?>
<div class="excursions__in-info__item">
    <div class="excursions__in-info__title">
        <?= Yii::t('app', 'ExcursionViewPage.citiesOnWay') ?>:
    </div>
    <div class="excursions__in-info__right">
        <?= join(', ', $cities) ?>
    </div>
</div>
<?php endif; ?>
