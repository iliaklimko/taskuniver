<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\components\helpers\BaseHelper;
use frontend\components\widgets\LinkPager\LinkPagerWidget as LinkPager;

$title = Yii::t('app', 'ExcursionsPage.title');
$h1 = $title;
$this->title = Html::encode($title);

if (!empty($excursionGroups['title'])) {
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'ExcursionViewPage.breadcrumb'), 'url' => Url::to(BaseHelper::mergeWithCurrentParams(['excursion/index', 'excursion_id' => null]))];
    $this->params['breadcrumbs'][] = $excursionGroups['title'];
} else {
    $this->params['breadcrumbs'][] = $h1;
}

?>

<div class="page-content workarea">
    <?= $this->render('//layouts/partials/breadcrumbs') ?>
    <div class="page-content__title">
        <div class="container-fluid">
            <h1><?= Html::encode($h1) ?></h1>
        </div>
    </div>
    <!-- CONTENT-PART -->
    <div class="container-fluid">
        <div class="page-content__wrapper">
            <?= $this->render('partials/excursion-groups', ['excursionGroups' => $excursionGroups['ob'], 'current' => $excursionGroups['current']]) ?>

            <?= $this->render('partials/top-filter') ?>
            <div class="excursions">
                <?= $this->render('partials/left-filter', [
                    'excursionTypeList' => $excursionTypeList,
                    'excursionThemeList' => $excursionThemeList,
                    'languageList' => $languageList,
                    'cityList' => $cityList,
                ]) ?>
                <div class="excursions__list row">
                    <?php if (count($excursionList) > 0): ?>
                    <?php foreach ($excursionList as $excursion): ?>
                        <?= $this->render('partials/excursion', ['model' => $excursion]) ?>
                    <?php endforeach; ?>
                    <?= LinkPager::widget([
                        'pagination' => $pagination,
                    ]); ?>
                    <?php else: ?>
                        <h2><?= Yii::t('app', 'SearchNotFoundPage.h2') ?></h2>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
