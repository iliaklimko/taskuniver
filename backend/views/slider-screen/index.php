<?php

use yii\helpers\Html;
use backend\components\widgets\GridView\GridViewWidget as GridView;
use backend\components\widgets\HeaderMenu\HeaderMenu;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\City */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Slider');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo HeaderMenu::widget([
])  ?>

<div class="city-index box box-primary">
     <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => false,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => [
                    'style' => 'width: 4%;',
                ],
            ],

            [
                'attribute' => 'alias',
                'headerOptions' => [
                    'style' => 'width: 15%;',
                ],
            ],

            [
                'label' => Yii::t('app', 'Image'),
                'format' => 'html',
                'value' => function ($model) {
                    return Html::img($model->getUploadUrl('image'), [
                        'style' => 'height: 100px;'
                    ]);
                },
                'headerOptions' => [
                    'style' => 'width: 20%;',
                ],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}',
                'headerOptions' => [
                    'style' => 'width: 15%;',
                ],
            ],
        ],
    ]); ?>
</div>
