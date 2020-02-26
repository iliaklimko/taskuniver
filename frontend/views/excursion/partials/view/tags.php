<?php

use yii\helpers\Url;
use frontend\components\helpers\BaseHelper;
?>

<?php if ($model->tagValues): ?>
<div class="excursions__in-info__item">
    <div class="excursions__in-info__title">
        <?= Yii::t('app', 'PostViewPage.tags') ?>:
    </div>
    <div class="excursions__in-info__right">
        <?php foreach ($model->tagValues as $tagValue): ?>
        <a class="tags__item"
           href="<?= Url::to(BaseHelper::mergeWithCurrentParams([
                                '/search/index',
                                'q' => $tagValue,
                                'excursion_id' => null,
                ])) ?>"
        ><?= $tagValue ?></a>
        <?php endforeach;?>
    </div>
</div>
<?php endif; ?>
