<?php

namespace backend\components\widgets\HeaderMenu;

use yii\base\Widget;

class HeaderMenu extends Widget
{
    public $brandLabel = false;

    public $brandUrl = '#';

    public $brandOptions = [];

    public $leftItems = [];

    public $rightItems = [];

    public function run()
    {
        return $this->render('header-menu', [
            'brandLabel' => $this->brandLabel,
            'brandUrl' => $this->brandUrl,
            'brandOptions' => $this->brandOptions,
            'leftItems' => $this->leftItems,
            'rightItems' => $this->rightItems,
        ]);
    }
}
