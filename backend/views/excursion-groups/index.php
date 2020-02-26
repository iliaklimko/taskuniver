<?php

use yii\helpers\Html;
use backend\components\widgets\GridView\GridViewWidget as GridView;
use backend\components\widgets\HeaderMenu\HeaderMenu;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ExcursionGroupsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Excursion categories');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo HeaderMenu::widget([
    'rightItems' => [
        ['label' => '<i class="fa fa-plus-circle"></i>' . ' ' . Yii::t('app', 'Add New'), 'url' => ['create']],
    ],
]) ?>

<div class="language-index box box-primary">

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
                'attribute' => 'excursion_groups_name',
                'label' => Yii::t('app', 'Name'),
                'value' => function ($model) {
                    return $model->name;
                },
            ],

//            'priority',
            [
                'attribute' => 'excursion_groups_code',
                'label' => Yii::t('app', 'Alias'),
                'value' => function ($model) {
                    return $model->code;
                },
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
