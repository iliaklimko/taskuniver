<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use frontend\components\widgets\LinkPager\LinkPagerWidget as LinkPager;
use frontend\components\widgets\OfficeMenu\OfficeMenuWidget as OfficeMenu;

$title = Yii::t('app', 'GuideExcursionsListPage.title');
$h1 = Yii::t('app', 'GuideExcursionsListPage.h1');

$this->title = Html::encode($title);
$this->params['breadcrumbs'][] = [
    'label' => $h1,
    'url'   => ['/user/edit-profile', 'locale' => Yii::$app->request->get('locale')],
];
$this->params['breadcrumbs'][] = $title;
?>

<div class="page-content workarea">
    <?= $this->render('//layouts/partials/breadcrumbs') ?>
    <div class="page-content__title">
        <div class="container-fluid">
            <h1><?= Html::encode($h1) ?></h1>
        </div>
    </div>

    <div class="container-fluid">
        <div class="page-content__wrapper">
            <div class="cabinet">
                <?= OfficeMenu::widget([
                    'showListExcursions' => $this->params['showListExcursions'],
                    'showListOrders'     => $this->params['showListOrders'],
                    'newOrdersCount'     => $this->params['newOrdersCount'],
                ]) ?>
                <?= ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemView' => '//excursion/partials/excursion',
                    'viewParams' => [
                        'divClass' => 'col-xs-4',
                        'editToolbar' => true,
                    ],
                    'layout' => "{items}\n{pager}",
                    'itemOptions' => [
                        'tag' => null,
                    ],
                    'options' => [
                        'class' => 'excursions__bottom row',
                    ],
                    'pager' => [
                        'class' => LinkPager::className(),
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>

<a href="#created" class="btn fb-inline" style="display: none;">Экскурсия</a>
<div id="created" class="dnone">
    <div class="popup">
        <div class="popup__title popup__title--centered"><?= Yii::t('app', 'GuideExcursionsListPage.popupCreatedTitle') ?></div>
        <div class="text-popup text-popup--centered">
            <?= Yii::t('app', 'GuideExcursionsListPage.popupCreatedBody') ?>
        </div>
    </div>
</div>
