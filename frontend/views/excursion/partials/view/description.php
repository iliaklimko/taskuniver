<?php

use yii\helpers\StringHelper;

$paragraphs = StringHelper::explode($model->description, $delimiter = "\n");
$intro = $paragraphs[0];
unset($paragraphs[0]);

$imagesFirst4 = array_slice($model->images, 0, 4);
$imagesLast = array_slice($model->images, 4);
?>

<div class="excursions__in-info__item">
    <div class="excursions__in-info__title">
        <?= Yii::t('app', 'ExcursionViewPage.description') ?>:
    </div>
    <div class="excursions__in-info__right">
        <div class="excursions__in-text">
            <?= $intro ?>
            <?php if (!empty($paragraphs)): ?>
            <div class="text-hidden">
                <?= nl2br(join("\n", $paragraphs)) ?>
            </div>
            <div class="text-hidden__toggle-wrap">
                <a href="#" class="text-hidden__toggle"><?= Yii::t('app', 'ExcursionViewPage.showDetails') ?></a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php if ($model->images): ?>
<div class="excursions__in-images">
    <?php foreach ($imagesFirst4 as $image): ?>
    <a href="<?= $image->getUploadUrl('filename') ?>"
        class="excursions__in-images__item fb-gallery"
        rel="gallery1"
        style="background:url(<?= $image->getUploadUrl('filename') ?>)"
    >
    </a>
    <?php endforeach; ?>
    <?php if (count($imagesLast) > 1): ?>
        <a href="<?= $imagesLast[0]->getUploadUrl('filename') ?>"
            class="excursions__in-images__item fb-gallery"
            rel="gallery1"
            style="background:url(<?= $imagesLast[0]->getUploadUrl('filename') ?>)"
        >
            <div class="img-hidden-toggle__wrap">
                <span><?= Yii::t('app', 'ExcursionViewPage.more') ?></span>
            </div>
        </a>
        <?php unset($imagesLast[0]); ?>
        <div class="excursions__in-images--hidden">
        <?php foreach ($imagesLast as $image): ?>
        <a href="<?= $image->getUploadUrl('filename') ?>"
            class="excursions__in-images__item fb-gallery"
            rel="gallery1"
            style="background:url(<?= $image->getUploadUrl('filename') ?>)"
        >
        </a>
        <?php endforeach; ?>
        </div>
    <?php elseif (count($imagesLast) == 1): ?>
        <a href="<?= $imagesLast[0]->getUploadUrl('filename') ?>"
            class="excursions__in-images__item fb-gallery"
            rel="gallery1"
            style="background:url(<?= $imagesLast[0]->getUploadUrl('filename') ?>)"
        >
        </a>
    <?php else: ?>
    <?php endif; ?>
</div>
<?php endif; ?>
