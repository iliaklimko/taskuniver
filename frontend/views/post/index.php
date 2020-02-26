<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use frontend\components\widgets\LinkPager\LinkPagerWidget as LinkPager;
use frontend\components\widgets\PostCategoryFilter\PostCategoryFilterWidget as PostCategoryFilter;
use frontend\components\helpers\BaseHelper;

$title = Yii::$app->request->get('post_category') != 'all'
    ? $this->params['postCategoryList'][Yii::$app->request->get('post_category')]->name
    : Yii::t('app', 'PostListPage.categoryAllTitle');
$h1 = $title;

$firstRow = array_slice($postList, 0, 3);
$secondRow = array_slice($postList, 3, 4);
$thirdRow = array_slice($postList, 7, 2);
$fourthRow = array_slice($postList, 9, 3);

$this->title = Html::encode($title);
if (Yii::$app->request->get('post_category') != 'all') {
    $this->params['breadcrumbs'][] = [
        'label' => Yii::t('app', 'PostListPage.categoryAllTitle'),
        'url' => Url::to(BaseHelper::mergeWithCurrentParams(['post/index', 'post_category' => 'all', 'post_alias' => null]))
    ];
    $this->params['breadcrumbs'][] = $h1;
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
                <?= PostCategoryFilter::widget() ?>
                <div class="news-wrap">
                    <div class="news row">
                        <?php foreach ($firstRow as $post): ?>
                            <?= $this->render('partials/post', [
                                'model' => $post,
                            ]) ?>
                        <?php endforeach; ?>
                        <?php foreach ($secondRow as $post): ?>
                            <?= $this->render('partials/post', [
                                'divClass' => 'col-xs-3',
                                'model' => $post,
                            ]) ?>
                        <?php endforeach; ?>
                        <?php foreach ($thirdRow as $post): ?>
                            <?= $this->render('partials/post', [
                                'divClass' => 'col-xs-6',
                                'model' => $post,
                            ]) ?>
                        <?php endforeach; ?>
                        <?php foreach ($fourthRow as $post): ?>
                            <?= $this->render('partials/post', [
                                'model' => $post,
                            ]) ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?= LinkPager::widget([
            'pagination' => $pagination,
        ]); ?>
        <div class="container-fluid">
            <!-- <div class="benefits workarea">
                <div class="benefits__inner">
                    <h2>Преимущества для гидов</h2>
                    <a href="#" class="btn btn--minimal">Зарегистрироваться</a>
                </div>
            </div> -->
        </div>
</div>
<!--end CONTENT-PART -->
