<?php

use yii\helpers\Html;
use yii\helpers\Url;

$title = Yii::t('app', 'CreateExcursionPage.title');
$h1 = $title;

$this->title = Html::encode($title);

$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'GuideEditProfilePage.h1'),
    'url'   => ['/user/edit-profile', 'locale' => Yii::$app->request->get('locale')],
];
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'OfficeMenuWidget.excursionsTab'),
    'url' => Url::to([
                '/user/list-excursions',
                'locale' => Yii::$app->request->get('locale'),
                'target_audience' => Yii::$app->request->get('target_audience'),
             ])
];
$this->params['breadcrumbs'][] = $h1;

$toggleLang = $lang == 'ru'
    ? ['lang' => 'en', 'label' => Yii::t('app', 'To english version')]
    : ['lang' => 'ru', 'label' => Yii::t('app', 'To russian version')]
;
?>

<div class="page-content workarea">
    <?= $this->render('//layouts/partials/breadcrumbs') ?>
    <div class="page-content__title">
        <div class="container-fluid">
            <h1><?= Html::encode($h1) ?></h1>
            <h2 class="language-version">
                <a href="<?= Url::to([
                                '/user/create-excursion',
                                'lang' => $toggleLang['lang'],
                                'locale' => Yii::$app->request->get('locale') != 'ru' ? Yii::$app->request->get('locale') : null,
                        ]) ?>">
                    <?= $toggleLang['label'] ?>
                </a>
            </h2>
        </div>
    </div>
    <?= $this->render('partials/create-excursion/_form', [
        'lang' => $lang,
        'model' => $model,
        'uploadForm' => $uploadForm,
        'cityList' => $cityList,
        'sightList' => $sightList,
        'languageList' => $languageList,
        'currencyList' => $currencyList,
        'excursionTypeList' => $excursionTypeList,
        'excursionThemeList' => $excursionThemeList,
        'targetAudienceList' => $targetAudienceList,
        'excursionGroups' => $excursionGroups,
    ]) ?>
</div>
