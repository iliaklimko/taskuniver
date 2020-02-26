<?php

$languages = array_map(function ($language) {
    return $language->name;
}, $model->languages);
?>

<div class="excursions__in-info__item">
    <div class="excursions__in-info__title">
        <?= Yii::t('app', 'ExcursionViewPage.languages') ?>:
    </div>
    <div class="excursions__in-info__right">
        <?= join(', ', $languages) ?>
    </div>
</div>
