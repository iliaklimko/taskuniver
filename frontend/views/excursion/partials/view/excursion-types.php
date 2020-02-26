<?php

$types = array_map(function ($type) {
    return $type->name;
}, $model->types);
?>

<div class="excursions__in-info__item">
    <div class="excursions__in-info__title">
        <?= Yii::t('app', 'ExcursionViewPage.excursionType') ?>:
    </div>
    <div class="excursions__in-info__right">
        <?= join(', ', $types) ?>
    </div>
</div>
