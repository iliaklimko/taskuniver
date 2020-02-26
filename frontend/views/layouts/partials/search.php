<?php

use yii\helpers\Url;
use frontend\components\helpers\BaseHelper;
?>

<form action="<?= Url::to(BaseHelper::mergeWithCurrentParams(['search/index'])) ?>" method="get">
    <div class="page-header__search">
        <div class="page-header__search-wrap">
            <div class="page-header__input-wrap">
                <input type="text"
                       class="page-header__input"
                       placeholder="<?= Yii::t('app', 'Header.searchForm.placeholder') ?>"
                       name="q"
                       value="<?= Yii::$app->request->get('q') ?>"
                       autocomplete="off"
                >
                <ul class="page-header__autocomplete">
                </ul>
            </div>
            <input type="submit" class="page-header__submit" value="<?= Yii::t('app', 'Header.searchForm.submitButton') ?>">
        </div>
    </div>
</form>
