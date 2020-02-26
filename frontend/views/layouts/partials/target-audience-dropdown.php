<?php

use yii\helpers\Url;
use common\models\TargetAudience;
use frontend\components\helpers\BaseHelper;

$defaultTargetAudience = TargetAudience::findOne(['alias' => TargetAudience::ALIAS_ALL]);

$activeTargetAudienceId = Yii::$app->request->get('target_audience', '');
$taList = $this->params['targetAudienceList'];
$taListFirstItem = $taList[$activeTargetAudienceId];
$taList = array_filter($taList, function ($item) use ($activeTargetAudienceId) {
    return $item->url_alias != $activeTargetAudienceId;
});
?>

<div class="page-header__select">
    <a href="<?= Url::to(BaseHelper::mergeWithCurrentParams([
        '',
        'target_audience' => empty($taListFirstItem->url_alias)
            ? null
            : $taListFirstItem->url_alias
    ])) ?>">
        <img src="/frontend/web/css/dist/<?= $taListFirstItem->getTopIconUrl() ?>" alt="">
        <i></i>
    </a>
    <ul class="page-header__select-list">
        <li class="page-header__select-item page-header__select-item--active">
            <a href="<?= Url::to(BaseHelper::mergeWithCurrentParams([
                '',
                'target_audience' => empty($taListFirstItem->url_alias)
                    ? null
                    : $taListFirstItem->url_alias
            ])) ?>">
                <img src="/frontend/web/css/dist/<?= $taListFirstItem->getTopIconUrl() ?>" alt="">
            </a>
        </li>
        <?php foreach ($taList as $taItem): ?>
        <li class="page-header__select-item">
            <a href="<?= Url::to(BaseHelper::mergeWithCurrentParams([
                '',
                'target_audience' => empty($taItem->url_alias)
                    ? null
                    : $taItem->url_alias
            ])) ?>">
                <img src="/frontend/web/css/dist/<?= $taItem->getTopIconUrl() ?>" alt="">
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
