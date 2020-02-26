<?php

use common\models\Order;
?>

<div class="application <?= $model->guide_status === Order::GUIDE_STATUS_NEW ? 'application--new' : null ?>">
    <?php if ($model->guide_status === Order::GUIDE_STATUS_NEW): ?>
    <div class="application__status application__status--new">
        <?= Yii::t('app', 'GuideOrdersListPage.orderStatusNew') ?>
    </div>
    <?php elseif ($model->guide_status === Order::GUIDE_STATUS_ACCEPTED): ?>
    <div class="application__status application__status--approved">
        <?= Yii::t('app', 'GuideOrdersListPage.orderStatusAccepted') ?>
    </div>
    <?php else: ?>
    <div class="application__status application__status--rejected">
        <?= Yii::t('app', 'GuideOrdersListPage.orderStatusRejected') ?>
    </div>
    <?php endif; ?>
    <a href="#" class="application__link">
        <span><?= Yii::t('app', 'GuideOrdersListPage.detailsToggle') ?></span><i></i>
    </a>
    <?php if($model->guide_status === Order::GUIDE_STATUS_NEW): ?>
    <div class="application__select">

        <a href="#renouncement" data-order-id="<?= $model->id ?>" class="fb-inline renouncement"><i></i><?= Yii::t('app', 'GuideOrdersListPage.rejectButton') ?></a>
        <a href="#confirmation" data-order-id="<?= $model->id ?>" class="btn-more fb-inline confirmation"><?= Yii::t('app', 'GuideOrdersListPage.acceptButton') ?></a>
    </div>
    <?php endif; ?>
    <div class="application__middle">
        <div class="application__date">
            <?php if ($model->date): ?>
                <?= Yii::t('app', 'OrderListPage.atDate') ?>
                <?= Yii::$app->formatter->asDate($model->date, 'php:j F Y') ?>
            <?php else: ?>
                <?= Yii::t('app', 'orderDateNotSet') ?>
            <?php endif; ?>
        </div>
        <div class="application__title">
            <?php
            if ($model->excursion->hasTranslation(Yii::$app->language)) {
                echo $model->excursion->title;
            } elseif (Yii::$app->language == 'ru' && !$model->excursion->hasTranslation('ru')) {
                echo $model->excursion->translate('en')->title;
            } elseif (Yii::$app->language == 'en' && !$model->excursion->hasTranslation('en')) {
                echo $model->excursion->translate('ru')->title;
            } else {
                echo 'No Name';
            }
            ?>
        </div>
    </div>
    <div class="application__info">
        <div class="application__inner">
            <div class="application__item">
                <div class="application__name"><?= Yii::t('app', 'GuideOrdersListPage.orderChargedAt') ?></div>
                <?= Yii::$app->formatter->asDate($model->created_at, 'php:j F Y') ?>
            </div>
            <div class="application__item">
                <div class="application__name"><?= Yii::t('app', 'GuideOrdersListPage.excursionIdColumn') ?></div>
                <?= $model->excursion->id ?>
            </div>
            <div class="application__item">
                <div class="application__name"><?= Yii::t('app', 'GuideOrdersListPage.nameColumn') ?></div>
                <?= htmlspecialchars($model->name) ?>
            </div>
            <div class="application__item">
                <div class="application__name"><?= Yii::t('app', 'GuideOrdersListPage.emailColumn') ?></div>
                <a href="mailto:<?= $model->email ?>" class="application__email"><?= $model->email ?></a>
            </div>
            <div class="application__item">
                <div class="application__name"><?= Yii::t('app', 'GuideOrdersListPage.phoneColumn') ?></div>
                <?= $model->phone ?>
            </div>
            <div class="application__item">
                <div class="application__name"><?= Yii::t('app', 'GuideOrdersListPage.personNumberColumn') ?></div>
                <?= $model->quantity ?>
            </div>

            <div class="application__item">
                <div class="application__name"><?= Yii::t('app', 'GuideOrdersListPage.prepaid') ?></div>
                <div class="application__price">
                    <span class="<?= Yii::$app->currency == 'RUB' ? 'rouble' : 'euro' ?>"><?= Yii::$app->currency == 'RUB' ? '₽' : '&euro;' ?></span>
                    <?= Yii::$app->formatter->asDecimal(
                        Yii::$app->currencyConverter->convert(
                                $model->currency,
                                !empty($model->prepayment) ? $model->prepayment / 100 : $model->price / 100
                        )
                    ) ?>
                </div>
            </div>

            <div class="application__item">
                <div class="application__name"><?= Yii::t('app', 'GuideOrdersListPage.needPaid') ?></div>
                <? if (!empty($model->prepayment)) { ?>
                    <div class="application__price">
                        <span class="<?= Yii::$app->currency == 'RUB' ? 'rouble' : 'euro' ?>"><?= Yii::$app->currency == 'RUB' ? '₽' : '&euro;' ?></span>
                        <?= Yii::$app->formatter->asDecimal(
                            Yii::$app->currencyConverter->convert($model->currency, (($model->price / 100) - ($model->prepayment / 100)))
                        ) ?>
                    </div>
                <? } else {
                    echo 0;
                } ?>
            </div>
        </div>
    </div>
</div>
