<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\components\helpers\BaseHelper;
use frontend\models\Excursion;

/* @var $excursionGroups array */
?>
<? if (!empty($excursionGroups)) { ?>
    <div class="search__links">
        <a class="search__links-item <?= empty($current) ? ' search__links-item--active' : '' ?>"
           href="/excursions"><?= Yii::t('app', 'PostCategoryFilterWidget.all') ?></a>
        <? foreach ($excursionGroups as $obGroup) { ?>
            <a class="search__links-item<?= $current == $obGroup->code ? ' search__links-item--active' : '' ?>"
               href="/excursions/<?= $obGroup->code ?>"><?= $obGroup->name ?></a>
        <? } ?>
    </div>
<? } ?>
