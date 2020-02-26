<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use backend\components\widgets\GridView\GridViewWidget as GridView;
use backend\components\widgets\HeaderMenu\HeaderMenu;
use backend\models\Excursion;
use common\models\UserGroup;
use kartik\daterange\DateRangePicker;
use backend\models\search\ExcursionSearch;

$urlCreator = function ($postId) {
    return function ($action, $model, $key) use ($postId) {
        $params = [
            $action,
            'id' => $key,
            'postId' => $postId,
        ];
        return Url::toRoute($params);
    };
};

$statusList = Excursion::getStatusList();

$concatItems = function ($items) {
    return nl2br(join(", \n", array_map(function ($item) {
        return $item->name;
    }, $items)));
};

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ExcursionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = join(' | ', [$searchModel->user->email, Yii::t('app', 'Excursions')]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['user/index', 'groupAlias' => UserGroup::ALIAS_GUIDE]];
$this->params['breadcrumbs'][] = ['label' => $searchModel->user->email, 'url' => ['user/update', 'id' => $searchModel->user->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Excursions');
?>

<?php echo HeaderMenu::widget([
        'rightItems' => [

        ],
])  ?>

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
                'attribute' => 'id',
                'headerOptions' => [
                    'style' => 'width: 4%;',
                ],
            ],

            [
                'format' => 'raw',
                'attribute' => 'excursion_title',
                'value' => function ($model) {
                    return Html::a(
                        StringHelper::truncate($model->title, 50),
                        ['excursion/update', 'id' => $model->id]
                    );
                },
            ],

            [
                'format' => 'raw',
                'label' => Yii::t('app', 'Target audiences'),
                'value' => function ($model) use ($concatItems) {
                    return $concatItems($model->targetAudiences);
                },
            ],

            [
                'attribute' => 'excursion_start_city',
                'value' => function ($model) {
                    return $model->startCity->name;
                }
            ],

            [
                'label' => Yii::t('app', 'Free').'/'.Yii::t('app', 'Pay'),
                'attribute' => 'excursion_price_status',
                'format' => 'raw',
                'value' => function ($model) {
                    $spanClass = $model->current_price > 0
                        ? 'label label-danger'
                        : 'label label-success';
                    return Html::tag('span', Excursion::priceStatus()[(int)$model->excursion_price_status], ['class' => $spanClass]);
                },
                'filterInputOptions' => ['prompt' => '<'.Yii::t('app', 'Free').'/'.Yii::t('app', 'Pay').'>', 'class'=> 'form-control'],
                'filter' => Excursion::priceStatus(),
            ],

            [
                'attribute' => 'publication_date',
                'format' => ['date', 'php:Y-m-d'],
                'filter' => DateRangePicker::widget([
                    'model'=> $searchModel,
                    'attribute' => 'publication_date_range',
                    'convertFormat' => true,
                    'pluginOptions' => [
                        'locale' => [
                            'format' => 'Y-m-d',
                            'separator'=> ExcursionSearch::DELIMITER,
                        ],
                    ]
                ]),
            ],

            // [
            //     'attribute' => 'created_at',
            //     'format' => ['date', 'php:Y-m-d H:i'],
            //     'filter' => false,
            // ],

            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:Y-m-d'],
                'filter' => false,
            ],

            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($model) use ($statusList) {
                    $spanClass = 'label label-info';
                    if ($model->status === Excursion::STATUS_ACCEPTED) {
                        $spanClass = 'label label-success';
                    }
                    if ($model->status === Excursion::STATUS_REJECTED) {
                        $spanClass = 'label label-danger';
                    }
                    return Html::tag(
                        'span',
                        $statusList[$model->status],
                        [
                            'class' => $spanClass,
                            'data-toggle' => 'tooltip',
                            'data-placement' => 'top',
                            'title' => $model->status === Excursion::STATUS_REJECTED
                                ? $model->rejection_reason
                                : ''
                        ]
                    );
                },
                'filterInputOptions' => ['prompt' => '<'.Yii::t('app', 'Status').'>', 'class'=> 'form-control'],
                'filter' => $statusList,
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
