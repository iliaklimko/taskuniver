<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;

NavBar::begin([
    'brandLabel' => $brandLabel,
    'brandUrl' => $brandUrl,
    'brandOptions' => $brandOptions,
    'renderInnerContainer' => false,
]);
    if ($leftItems) {
        echo Nav::widget([
            'items' => $leftItems,
            'encodeLabels' => false,
            'options' => ['class' => 'navbar-nav'],
        ]);
    }

    if ($rightItems) {
        echo Nav::widget([
            'items' => $rightItems,
            'encodeLabels' => false,
            'options' => ['class' => 'navbar-nav navbar-right'],
        ]);
    }

NavBar::end();
