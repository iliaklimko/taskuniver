<?php

$included = explode(',', $model->included_in_price);
$notIncluded = explode(',', $model->not_included_in_price);
?>

<div class="excursions__in-included">
    <?php if (Yii::$app->user->isGuest): ?>
    <a href="#booking" class="btn btn--minimal fb-inline fb-inline--entry"><?= Yii::t('app', 'ExcursionViewPage.bookNowShort') ?></a>
    <?php endif; ?>
    <?php if ($included): ?>
    <div class="excursions__in-included__title">
        <?= Yii::t('app', 'ExcursionViewPage.included') ?>:
    </div>
    <?php foreach ($included as $include): ?>
    <div class="excursions__in-included__item excursions__in-included__item--positive">
        <i></i>
        <?= $include ?>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
    <?php if ($notIncluded): ?>
    <div class="excursions__in-included__title">
        <?= Yii::t('app', 'ExcursionViewPage.notIncluded') ?>:
    </div>
    <?php foreach ($notIncluded as $notInclude): ?>
    <div class="excursions__in-included__item excursions__in-included__item--negative">
        <i></i>
        <?= $notInclude ?>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
