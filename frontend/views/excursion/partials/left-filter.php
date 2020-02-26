<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="excursions__filter">
    <div class="excursions__filter-view">
        <?= Html::beginForm(
            Url::to([
                'excursion/index',
                'target_audience' => Yii::$app->request->get('target_audience'),
                'locale' => Yii::$app->language != 'ru' ? Yii::$app->language : null,
                'order_by' => Yii::$app->request->get('order_by'),
            ]),
            'get',
            ['id' => 'left-filter-form']
        ) ?>
            <!-- <input type="reset" value="<?= Yii::t('app', 'ExcursionsPage.filter.reset') ?>" class="excursions__reset"
                id="exursions-filter-reset"
                data-reset-url="<?= Url::to([
                    'excursion/index',
                    'locale' => Yii::$app->request->get('locale'),
                    'target_audience' => Yii::$app->request->get('target_audience'),
                ]) ?>"
            > -->
            <h2 class="excursions__filter-title"><?= Yii::t('app', 'ExcursionsPage.filter.h2') ?></h2>
            <?= $this->render('data-dropdown') ?>
            <?= $this->render('type-dropdown', ['excursionTypeList' => $excursionTypeList]) ?>
            <?= $this->render('language-dropdown', ['languageList' => $languageList]) ?>
            <?= $this->render('duration-dropdown') ?>
            <?= $this->render('person-number-dropdown') ?>
            <?= $this->render('price-status-dropdown') ?>
            <?= $this->render('start-city-dropdown.php', ['cityList' => $cityList]) ?>
            <?= $this->render('target-audience-dropdown.php') ?>
            <?= $this->render('theme-dropdown', ['excursionThemeList' => $excursionThemeList]) ?>
            <div class="excursions__filter-submit-wrap">
                <input type="submit"
                    value="<?= Yii::t('app', 'ExcursionsPage.filter.apply') ?>"
                    class="excursions__filter-submit"
                    id="submit-left-filter"
                >
                <input type="reset" value="<?= Yii::t('app', 'ExcursionsPage.filter.reset') ?>" class="excursions__reset"
                    id="exursions-filter-reset"
                    data-reset-url="<?= Url::to([
                        'excursion/index',
                        'locale' => Yii::$app->request->get('locale'),
                        'target_audience' => Yii::$app->request->get('target_audience'),
                    ]) ?>"
                >
            </div>
        </form>
    </div>
</div>
