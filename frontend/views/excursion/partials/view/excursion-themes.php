<?php

$themes = array_map(function ($theme) {
    return $theme->name;
}, $model->themes);
?>

<div class="excursions__in-info__item">
    <div class="excursions__in-info__title">
        <?= Yii::t('app', 'ExcursionViewPage.excursionTheme') ?>:
    </div>
    <div class="excursions__in-info__right">
        <?= join(', ', $themes) ?>
    </div>
</div>
