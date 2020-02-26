<?php

use yii\helpers\Url;
use frontend\components\helpers\BaseHelper;
?>

<header class="page-header">
    <div class="container-fluid">
        <?= $this->render('target-audience-dropdown') ?>
        <div class="page-header__links">
            <?= $this->render('lang-dropdown') ?>
            <?= $this->render('currency-dropdown') ?>
        </div>
    </div>
</header>
