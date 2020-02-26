<?php

use yii\helpers\Url;
use frontend\components\helpers\BaseHelper;
?>

<?php if ($model->sights): ?>
<div class="excursions__in-info__item">
    <div class="excursions__in-info__title">
        <?= Yii::t('app', 'ExcursionViewPage.sights') ?>:
    </div>
    <div class="excursions__in-info__right">
        <?php foreach ($model->sights as $sight): ?>
        <a class="tags__item"
           href="<?= Url::to(BaseHelper::mergeWithCurrentParams([
                                '/search/index',
                                'q' => $sight->name,
                                'excursion_id' => null,
                ])) ?>"
        ><?= $sight->name ?></a>
        <?php endforeach;?>
    </div>
</div>
<?php endif; ?>
