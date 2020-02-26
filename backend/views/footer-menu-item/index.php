<?php

use yii\helpers\Html;
use backend\components\widgets\GridView\GridViewWidget as GridView;
use backend\components\widgets\HeaderMenu\HeaderMenu;
use common\models\FooterMenuItem;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\City */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Footer menu');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo HeaderMenu::widget([
        'rightItems' => [
            ['label' => '<i class="fa fa-plus-circle"></i>' . ' ' . Yii::t('app', 'Add New'), 'url' => ['create']],
        ],
])  ?>

<div class="city-index box box-primary">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => [
                    'style' => 'width: 4%;',
                ],
            ],

            [
                'attribute' => 'menu_item_title',
                'label' => 'Title',
                'value' => function ($model) {
                    return $model->title;
                },
            ],

            [
                'attribute' => 'column',
                'filterInputOptions' => ['prompt' => '', 'class'=> 'form-control'],
                'filter' => FooterMenuItem::getColumnList(),
            ],

            [
                'attribute' => 'position',
                'label' => 'Piority',
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'headerOptions' => [
                    'style' => 'width: 15%;',
                ],
            ],
        ],
    ]); ?>
</div>
