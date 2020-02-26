<?php

use yii\helpers\Url;

$this->title = Yii::t('app', 'ErrorPage.title {statusCode} {message}', [
    'statusCode' => $exception->statusCode,
    'message'    => $exception->statusCode == 404 ? Yii::t('app', 'ErrorPage.404message') : null
]);
?>

<div class="page-content workarea">
    <!-- CONTENT-PART -->
    <div class="page404">
        <div class="page404__inner" data-section>
            <div class="page404__wrap">
                <div class="page404__item">
                    <strong><?= Yii::t('app', 'ErrorPage.h1') ?></strong>
                    <?php if ($exception->statusCode == 404): ?>
                    <p><?= Yii::t('app', 'ErrorPage.heading') ?></p>
                    <?php else: ?>
                    <p></p>
                    <?php endif; ?>
                    <?= Yii::t('app', 'ErrorPage.usefulLinks') ?>
                    <div class="page404__link">
                        <a href="<?= Url::to(['post/index', 'locale' => Yii::$app->language != 'ru' ? Yii::$app->language : null, 'post_category' => 'all']) ?>"><?= Yii::t('app', 'ErrorPage.usefulLinkMain') ?></a>
                    </div>
                    <div class="page404__link">
                        <a href="<?= Url::to(['excursion/index', 'locale' => Yii::$app->language != 'ru' ? Yii::$app->language : null]) ?>">
                            <?= Yii::t('app', 'ErrorPage.usefulLinkPosts') ?>
                        </a>
                    </div>
                </div>
                <div class="page404__bg">
                    <div class="baloon"></div>
                    <div class="cloud1"></div>
                    <div class="cloud2"></div>
                    <div class="cloud3"></div>
                    <div class="cloud4"></div>
                    <div class="cloud5"></div>
                </div>
            </div>
        </div>
    </div>
</div>
