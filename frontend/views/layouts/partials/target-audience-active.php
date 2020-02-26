<?php

use yii\helpers\Url;
use common\models\TargetAudience;
use frontend\components\helpers\BaseHelper;

$defaultTargetAudience = TargetAudience::findOne(['alias' => TargetAudience::ALIAS_ALL]);

$activeTargetAudienceId = Yii::$app->request->get('target_audience', '');
$taList = $this->params['targetAudienceList'];
$taListFirstItem = $taList[$activeTargetAudienceId];
?>

<div class="page-header__select">
    <a href="<?= Url::to(['main-page/index', 'target_audience' => empty($taListFirstItem->url_alias) ? null : $taListFirstItem->url_alias]) ?>">
        <img src="/frontend/web/css/dist/<?= $taListFirstItem->getTopIconUrl() ?>" alt="">
    </a>
</div>
