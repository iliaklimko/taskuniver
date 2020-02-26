<?php

use yii\helpers\Url;
use frontend\components\helpers\BaseHelper;
?>

<header class="page-header">
    <div class="container-fluid">
        <a href="<?= Url::to(['main-page/index', 'locale' => Yii::$app->request->get('locale')]) ?>" class="page-header__logo">
            <img src="/frontend/web/css/dist/img/content/top-logo.png" alt="">
        </a>
        <?= $this->render('target-audience-active') ?>
        <div class="page-header__links">
            <?= $this->render('lang-dropdown') ?>
            <?= $this->render('currency-dropdown') ?>
            <?php if (Yii::$app->user->isGuest): ?>
            <a href="<?= Url::to(BaseHelper::mergeWithCurrentParams(['favorites/index', 'q' => null])) ?>" class="page-header__favorite">
                <i></i>
                <span><?= Yii::t('app', 'Header.favorites') ?></span>
            </a>
            <?php endif; ?>
        </div>
        <?= $this->render('search') ?>
    </div>
</header>
