<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ProfileForm */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use frontend\components\widgets\LinkPager\LinkPagerWidget as LinkPager;
use frontend\components\widgets\OfficeMenu\OfficeMenuWidget as OfficeMenu;
use frontend\assets\ConfirmOrderAsset;

ConfirmOrderAsset::register($this);

$title = Yii::t('app', 'GuideOrdersListPage.title');
$h1 = Yii::t('app', 'GuideOrdersListPage.h1');

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
                    'itemView' => 'partials/list-orders/order',
                    'layout' => "{items}\n{pager}",
                    'itemOptions' => [
                        'tag' => null,
                    ],
                    'options' => [
                        'class' => 'application-wrap',
                    ],
                    'pager' => [
                        'class' => LinkPager::className(),
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
<?= $this->render('partials/list-orders/popup', [
    'confirmation' => $confirmation,
    'renouncement' => $renouncement,
]) ?>
