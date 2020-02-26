<?php

namespace frontend\components\widgets\SocialSeo;

use Yii;
use yii\base\Widget;

class SocialSeoWidget extends Widget
{
    public $meta;

    public function run()
    {
        return $this->render('view', [
            'meta' => $this->meta,
        ]);
    }
}
