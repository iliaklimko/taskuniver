<?php

use yii\helpers\Html;
use backend\components\widgets\GridView\GridViewWidget as GridView;
use backend\components\widgets\HeaderMenu\HeaderMenu;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\SightSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Sight');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo HeaderMenu::widget([
        'rightItems' => [
            ['label' => '<i class="fa fa-plus-circle"></i>' . ' ' . Yii::t('app', 'Add New'), 'url' => ['create']],
        ],
])  ?>

<div class="currency-index box box-primary">

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
                'attribute' => 'sight_name',
                'label' => Yii::t('app', 'Name'),
                'value' => function ($model) {
                    return $model->translate('en')->name;
                },
            ],

            [
                'label' => Yii::t('app', 'City'),
                'attribute' => 'city_id',
                'enableSorting' => false,
                'filter' => false,
                'value' => function ($model) {
                    return $model->city
                        ? $model->city->translate('en')->name
                        : null;
                }
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
