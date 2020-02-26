<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\widgets\ListView;
use frontend\components\helpers\BaseHelper;
use frontend\assets\FileAttachAsset;

$currentLanguage = Yii::$app->language != 'ru' ? Yii::$app->language : null;

FileAttachAsset::register($this);

if ($post->meta_description) {
    $this->registerMetaTag([
        'name' => 'description',
        'content' => $post->meta_description,
    ]);
}
if ($post->meta_keywords) {
    $this->registerMetaTag([
        'name' => 'keywords',
        'content' => $post->meta_keywords,
    ]);
}

$title = $post->title;
$h1 = $post->h1 ?: $title;
$author = $post->author;

$this->params['specialSeo']['title'] = $title;
$this->params['specialSeo']['description'] = $post->meta_description;
$this->params['specialSeo']['image'] = $post->getUploadUrl('image');

$this->title = Html::encode($title);
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'PostListPage.categoryAllTitle'),
    'url' => Url::to(BaseHelper::mergeWithCurrentParams(['post/index', 'post_category' => 'all', 'post_alias' => null, 'locale' => $currentLanguage]))
];
$this->params['breadcrumbs'][] = $h1;
?>

<div class="page-content workarea">
    <?= $this->render('//layouts/partials/breadcrumbs') ?>
    <div class="page-content__title">
        <div class="container-fluid">
            <div class="page-content__title-labels">
                <?php foreach ($post->postCategories as $postCategory): ?>
                <a class="page-content__title-labels__item"
                    href="<?= Url::to(['/post/index', 'target_audience' => Yii::$app->request->get('target_audience'), 'post_category' => $postCategory->url_alias, 'locale' => $currentLanguage]) ?>"
                >
                    <?= $postCategory->name ?>
                </a>
                <?php endforeach; ?>
            </div>
            <h1><?= Html::encode($h1) ?></h1>
        </div>
    </div>
    <!-- CONTENT-PART -->
    <div class="container-fluid">
        <div class="page-content__wrapper">
            <div class="author">
                <time class="author__date"
                    datetime="<?= $post->publication_date ?>"
                >
                    <?= Yii::$app->formatter->asDate($post->publication_date, 'php:j F Y') ?>
                </time>
                <div class="author__item">
                    <!-- <div
                        class="author__img"
                        style="<?php /*echo $author->getUploadUrl('avatar') ? 'background:url('.$author->getUploadUrl('avatar').')' : ''*/ ?>"
                    ></div> -->
                    <a class="author__name"><?= Html::encode($post->user_name) ?></a>
                    <a class="author__name"><?php /*echo Html::encode($author->full_name)*/ ?></a>
                    <!-- <a href="<?php /*echo Url::to(['user/view-profile', 'id' => $author->id])*/ ?>" class="author__name"><?php /*echo Html::encode($author->full_name)*/ ?></a> -->
                </div>
                <div class="author__item"><?= $post->startCityName() ?></div>
            </div>
            <div class="post-body"><?= $post->body ?></div>
            <?php if ($post->galleryItems): ?>
            <div class="text-slider-wrap">
                <div class="text-slider">
                    <?php foreach ($post->galleryItems as $galleryItem): ?>
                    <div class="text-slider__item">
                        <img src="<?= $galleryItem->getUploadUrl('filename') ?>" alt="">
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="slide-count-wrap">
                    <div class="slide-count-wrap__inner">
                        <span class="current"></span>/<span class="total"></span>
                    </div>
                 </div>
            </div>
            <?php endif; ?>

            <div class="tags">
                <div class="share">
                    <span><?= Yii::t('app', 'ExcursionViewPage.share') ?>:</span>
                    <?= $this->render('partials/sharethis') ?>
                </div>
                <?php if ($post->tagValues): ?>
                <div class="tags__list">
                    <span><?= Yii::t('app', 'PostViewPage.tags') ?>:</span>
                    <?php foreach ($post->tagValues as $tagValue): ?>
                    <a class="tags__item"
                       href="<?= Url::to(BaseHelper::mergeWithCurrentParams(['/search/index', 'post_alias' => null, 'q' => $tagValue])) ?>"
                    ><?= $tagValue ?></a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <div class="tags__offer">
                    <a href="#publication" class="offer_span fb-inline"><span><?= Yii::t('app', 'PostViewPage.offer') ?></span></a>
                    <a href="#publication" class="tags__offer-link fb-inline"></a>
                </div>
            </div>
        </div>
    </div>
    <?php if ($dataProvider->totalCount > 0): ?>
    <div class="news__page">
        <div class="container-fluid">
            <h2><?= Yii::t('app', 'PostViewPage.more') ?></h2>
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => 'partials/post',
                'layout' => '{items}',
                'itemOptions' => [
                    'tag' => null,
                ],
                'options' => [
                    'class' => 'news row',
                ],
            ]); ?>
        </div>
    </div>
    <?php endif; ?>
    <?php if ($excursionProvider->totalCount > 0): ?>
    <div class="excursions__bottom">
        <div class="container-fluid">
            <h2><?= Yii::t('app', 'PostViewPage.moreExcursions') ?></h2>
            <?= ListView::widget([
                'dataProvider' => $excursionProvider,
                'itemView' => '//excursion/partials/excursion',
                'viewParams' => ['divClass' => 'col-xs-4'],
                'layout' => '{items}',
                'itemOptions' => [
                    'tag' => null,
                ],
                'options' => [
                    'class' => 'row',
                ],
            ]); ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="comments">
        <?= $this->render('partials/hypercomments') ?>
    </div>
</div>

<?= $this->render('partials/submit-post-popup', ['submitPostForm' => $submitPostForm]) ?>
<?= $this->render('partials/success-submit-popup') ?>
