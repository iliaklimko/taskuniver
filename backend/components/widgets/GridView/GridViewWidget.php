<?php

namespace backend\components\widgets\GridView;

use yii\grid\GridView;

class GridViewWidget extends GridView
{
    public $options = ['class' => 'grid-view table-responsive'];
    public $tableOptions = ['class' => 'table table-hover table-bordered table-condensed'];
}
