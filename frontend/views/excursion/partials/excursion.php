<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\components\helpers\BaseHelper;
use frontend\models\Excursion;

$localeListExcursion = Yii::$app->request->get('locale');
if (Yii::$app->controller->action->id == 'list-excursions') {
    if (Yii::$app->language == 'ru' && !$model->hasTranslation('ru')) {
        $localeListExcursion = 'en';
    }  elseif (Yii::$app->language == 'en' && !$model->hasTranslation('en')) {
        $localeListExcursion = null;
    }
}

$divClass = isset($divClass) ? $divClass : 'col-xs-4';
$editToolbar = isset($editToolbar) ? $editToolbar : false;

/* @var $divClass string */
/* @var $editToolbar boolean */
?>

<div class="<?= $divClass ?> excursions__item">
    <div class="excursions__item-wrap">
        <?php if (Yii::$app->user->isGuest): ?>
        <a href="#"
           class="excursions__item-favorite"
           data-model-id="<?= $model->id ?>"
        >
            <span><?= Yii::t('app', 'Favorites.addToFavoritesTooltip') ?></span>
        </a>
        <?php endif;?>
        <div class="excursions__item-inner">
            <div class="excursions__item-img-wrap">
                <a class="excursions__item-img"
                    style="background:url(<?= $model->getUploadUrl('featured_image') ?>)"
                    href="<?= Url::to(BaseHelper::mergeWithCurrentParams([
                        '/excursion/view',
                        'locale'          => Yii::$app->request->get('locale'),
                        'excursion_id'    => $model->id,
                        'target_audience' => Yii::$app->request->get('target_audience'),
                        'type'            => null,
                        'theme'           => null,
                        'language'        => null,
                        'duration'        => null,
                        'person_number'   => null,
                        'start_city'      => null,
                        'price_status'    => null,
                        'order_by'        => null,
                        'q' => null,
                        'group_code'      => !empty($model['excursion_groups']->code) ? $model['excursion_groups']->code : null,
                    ])) ?>"
                >
                </a>
                <div class="excursions__item-info">
                <?php if ($model->getNearestDate()): ?>
                <div class="excursions__item-img__date">
                    <div class="excursions__item-img__date-wrapper">
                        <div>
                            <?= Yii::$app->formatter->asDate($model->getNearestDate(), 'php:j F Y') ?>
                        </div>
                        <div>
                            <?= Yii::t('app', 'ExcursionViewPage.atTime') ?>
                            <?= $model->getNearestTime() ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <div class="excursions__item-img__label">
                    <?php if ($model->current_price * 100 > 0): ?>
                        <div class="old-price">
                            <?php if ($model->old_price * 100 > 0): ?>
                            <?= Yii::$app->formatter->asDecimal(
                                Yii::$app->currencyConverter
                                    ->setTo(Yii::$app->currency)->convert(
                                        $model->currency->code,
                                        $model->old_price
                                    ),
                                2
                            ) ?>
                            <span class="<?= Yii::$app->currency == 'RUB' ? 'rouble' : 'euro' ?>"><?= Yii::$app->currency == 'RUB' ? '₽' : '&euro;' ?></span>
                            <?php endif; ?>
                        </div>
                        <?= Yii::$app->formatter->asDecimal(
                            Yii::$app->currencyConverter
                                ->setTo(Yii::$app->currency)->convert(
                                    $model->currency->code,
                                    $model->current_price
                                ),
                            2
                        ) ?>
                        <span class="<?= Yii::$app->currency == 'RUB' ? 'rouble' : 'euro' ?>"><?= Yii::$app->currency == 'RUB' ? '₽' : '&euro;' ?></span>
                    <?php else: ?>
                        <?= Yii::t('app', 'FreePrice') ?>
                    <?php endif; ?>
                </div>
                </div>
            </div>
            <div class="news__icons">
                <?php foreach ($model->targetAudiences as $ta): ?>
                <span class="<?= $ta->getAudienceClass() ?>"></span>
                <?php endforeach; ?>
            </div>
            <div class="excursions__item-txt">
                <div class="excursions__item-labels">
                    <?php foreach ($model->themes as $theme): ?>
                    <a class="excursions__item-labels__item"
                        href="<?= Url::to([
                            '/excursion/index',
                            'theme' => $theme->id,
                            'locale'          => Yii::$app->request->get('locale'),
                            'target_audience' => Yii::$app->request->get('target_audience'),
                            'type'            => Yii::$app->request->get('type'),
                            'language'        => Yii::$app->request->get('language'),
                            'duration'        => Yii::$app->request->get('duration'),
                            'person_number'   => Yii::$app->request->get('person_number'),
                            'start_city'      => Yii::$app->request->get('start_city'),
                            'price_status'    => Yii::$app->request->get('price_status'),
                            'order_by'        => Yii::$app->request->get('order_by'),
                        ]) ?>"
                    ><?= $theme->name ?></a>
                    <?php endforeach; ?>
                </div>
                <a class="excursions__item-title"
                    href="<?= Url::to(BaseHelper::mergeWithCurrentParams([
                        '/excursion/view',
                        'excursion_id'    => $model->id,
                        'locale'          => $localeListExcursion,
                        'target_audience' => Yii::$app->request->get('target_audience'),
                        'type'            => null,
                        'theme'           => null,
                        'language'        => null,
                        'duration'        => null,
                        'person_number'   => null,
                        'start_city'      => null,
                        'price_status'    => null,
                        'order_by'        => null,
                        'q' => null,
                        'group_code'      => !empty($model['excursion_groups']->code) ? $model['excursion_groups']->code : null,
                    ])) ?>"
                >
                    <?php
                    if (Yii::$app->controller->action->id == 'list-excursions') {
                        if ($model->hasTranslation(Yii::$app->language)) {
                            echo $model->title;
                        } elseif (Yii::$app->language == 'ru' && !$model->hasTranslation('ru')) {
                            echo $model->translate('en')->title;
                        } elseif (Yii::$app->language == 'en' && !$model->hasTranslation('en')) {
                            echo $model->translate('ru')->title;
                        } else {
                            echo 'No Name';
                        }
                    } else {
                        echo $model->title;
                    }
                    ?>
                </a>
                <div class="excursions__item-city"><?= $model->startCityName() ?></div>
            </div>
        </div>
        <?php if (Yii::$app->user->isGuest || (!Yii::$app->user->isGuest && Yii::$app->user->id != $model->user->id)): ?>
            <div class="excursions__item-hover">
                <div class="excursions__item-hover__info">
                    <?= join(', ', array_map(function ($type) {
                        return $type->name;
                    }, $model->types)) ?>
                </div>
                <div class="author__item">
                    Гид:
                    <?php if ($model->user->getUploadUrl('avatar')): ?>
                    <div class="author__img" style="background:url(<?= $model->user->getUploadUrl('avatar') ?>)"></div>
                    <?php endif; ?>
                    <a class="author__name"><?= $model->user->getFullName() ?></a>
                </div>
            </div>
        <?php else: ?>
            <div class="excursions__item-hover">
                <div class="excursions__item-hover__links">
                    <a href="<?= Url::to([
                                    '/user/update-excursion',
                                    'id' => $model->id,
                                    'locale' => Yii::$app->request->get('locale') != 'ru' ? Yii::$app->request->get('locale') : null,
                                    'lang' => $model->hasTranslation('ru') ? 'ru' : 'en',
                            ]) ?>"
                        class="edit"
                    >
                        <img src="/frontend/web/css/dist/img/svg/edit.svg" alt="">
                    </a>
                    <?= Html::a(
                        '<img src="/frontend/web/css/dist/img/svg/delete.svg" alt="">',
                        ['/user/delete-excursion', 'id' => $model->id, 'locale' => Yii::$app->request->get('locale')],
                        [
                            'data-method' => 'post',
                            'data-pjax' => '0',
                            'class' => 'delete',
                        ]
                    ) ?>
                </div>
            </div>
        <?php endif;?>
    </div>
</div>
