<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use backend\components\widgets\GridView\GridViewWidget as GridView;
use backend\components\widgets\HeaderMenu\HeaderMenu;
use backend\assets\ModerationAsset;
use kartik\dialog\Dialog;
use common\models\Excursion;
use common\models\TargetAudience;
use kartik\daterange\DateRangePicker;
use backend\models\search\ExcursionSearch;
use yii2mod\alert\AlertAsset;
use common\models\ExcursionGroups;

AlertAsset::register($this);

$targetAudienceIds = ArrayHelper::map(
    TargetAudience::find()->with('translations')->andWhere(['not', ['alias' => 'all']])->all(),
    'id',
    'name'
);

// handle .btn-reject click
ModerationAsset::register($this);
echo Dialog::widget([
    'options' => [
        'type' => Dialog::TYPE_DANGER,
        'title' => Yii::t('app', 'Reject'),
    ],
]);

$statusList = Excursion::getStatusList();

$concatItems = function ($items) {
    return nl2br(join(", \n", array_map(function ($item) {
        return $item->name;
    }, $items)));
};

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\ExcursionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Excursions');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo HeaderMenu::widget([
        'rightItems' => [
            ['label' => '<i class="fa fa-plus-circle"></i>' . ' ' . Yii::t('app', 'Add New'), 'url' => ['create', 'langCode' => 'ru']],
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
                    'style' => 'width: 3%;',
                ],
            ],

            [
                'attribute' => 'id',
                'headerOptions' => [
                    'style' => 'width: 3%;',
                ],
            ],

            [
                'label' => Yii::t('app', 'Guide'),
                'attribute' => 'excursion_user',
                'value' => function ($model) {
                    return $model->user
                        ? $model->user->full_name
                        : null;
                },
                'headerOptions' => [
                    'style' => 'width: 20%;',
                ],
            ],

            [
                'format' => 'raw',
                'attribute' => 'excursion_title',
                'value' => function ($model) {
                    $title = !$model->hasTranslation('ru') ? $model->title : $model->translate('ru')->title;
                    $langCode = $model->hasTranslation('ru') ? 'ru' : 'en';
                    return Html::a(
                        StringHelper::truncate($title, 50),
                        ['excursion/update', 'id' => $model->id, 'langCode' => $langCode]
                    );
                },
                'headerOptions' => [
                    'style' => 'width: 20%;',
                ],
            ],

            [
                'format' => 'raw',
                'attribute' => 'excursion_audience',
                'label' => Yii::t('app', 'Target audiences'),
                'value' => function ($model) use ($concatItems) {
                    return $concatItems($model->targetAudiences);
                },
                'filterInputOptions' => ['prompt' => '', 'class'=> 'form-control'],
                'filter' => $targetAudienceIds,
                'headerOptions' => [
                    'style' => 'width: 6%;',
                ],
            ],

            [
                'label' => Yii::t('app', 'View on site'),
                'format' => 'raw',
                'value' => function ($model) {
                    $group = ExcursionGroups::find()->select('code')->where(['id' => $model->group_id])->one();
        
                    $urlParams = [
                        'excursion/view',
                        'excursion_id' => $model->id,
                        'mode' => 'force',
                        'group_code' => $group->code
                    ];
                    $raw = [];
                    if ($model->hasTranslation('ru')) {
                        $raw[] = Html::a(
                            '<i class="fa fa-external-link"></i> RUS',
                            Yii::$app->urlManagerFrontend->createAbsoluteUrl($urlParams),
                            ['target' => '_blank']
                        );
                    }
                    if ($model->hasTranslation('en')) {
                        $raw[] = Html::a(
                            '<i class="fa fa-external-link"></i> ENG',
                            Yii::$app->urlManagerFrontend->createAbsoluteUrl($urlParams+['locale'=>'en']),
                            ['target' => '_blank']
                        );
                    }
                    return join('<br>', $raw);
                },
                'headerOptions' => [
                    'style' => 'width: 3%;',
                ],
            ],

            [
                'attribute' => 'excursion_start_city',
                'value' => function ($model) {
                    return $model->startCity->name;
                },
                'headerOptions' => [
                    'style' => 'width: 4%;',
                ],
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
                'filterInputOptions' => ['prompt' => '', 'class'=> 'form-control'],
                'filter' => Excursion::priceStatus(),
                'headerOptions' => [
                    'style' => 'width: 2%;',
                ],
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
                'headerOptions' => [
                    'style' => 'width: 4%;',
                ],
            ],

            // [
            //     'attribute' => 'created_at',
            //     'format' => ['date', 'php:Y-m-d'],
            //     'filter' => false,
            // ],

            [
                'attribute' => 'updated_by_owner',
                'format' => 'raw',
                'label' => 'Updated By Owner',
                'value' => function ($model) {
                    return $model->updated_by_owner
                        ? Html::tag(
                            'span',
                            Yii::$app->formatter->asDate($model->updated_at, 'php:Y-m-d'),
                            ['class' => 'label label-warning']
                        )
                        : '';
                },
                'filter' => false,
                'headerOptions' => [
                    'style' => 'width: 4%;',
                ],
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
                'filterInputOptions' => ['prompt' => '', 'class'=> 'form-control'],
                'filter' => $statusList,
                'headerOptions' => [
                    'style' => 'width: 4%;',
                ],
            ],


            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{accept} {reject} {delete}',
                'visibleButtons' => [
                    'accept' => function ($model) {
                        return $model->status === Excursion::STATUS_NEW
                            || $model->status === Excursion::STATUS_REJECTED;
                    },
                    'delete' => function ($model) {
                        return $model->status === Excursion::STATUS_ACCEPTED
                            || $model->status === Excursion::STATUS_REJECTED;
                    },
                ],
                'buttons' => [
                    'accept' => function ($url) {
                        return Html::a(join(' ', [
                                '<span class="glyphicon glyphicon-thumbs-up"></span>',
                                Yii::t('app', 'Accept')
                            ]),
                            $url,
                            [
                                'class' => 'btn btn-xs btn-success',
                                'style' => 'margin-bottom: 5px;',
                                'title' => Yii::t('app', 'Accept'),
                                'aria-label' => Yii::t('app', 'Accept'),
                                'data-confirm' => Yii::t('app', 'Accept').'?',
                                'data' => [
                                    'method' => 'post',
                                    'pjax' => '0',
                                ],
                            ]
                        );
                    },
                    'reject' => function ($url, $model) {
                        return Html::a(join(' ', [
                                '<span class="glyphicon glyphicon-thumbs-down"></span>',
                                Yii::t('app', 'Reject')
                            ]),
                            $url,
                            [
                                'class' => 'btn btn-xs btn-danger btn-reject',
                                'style' => 'margin-bottom: 5px;',
                                'title' => Yii::t('app', 'Reject'),
                                'aria-label' => Yii::t('app', 'Reject'),
                            ]
                        );
                    },
                ],
                'headerOptions' => [
                    'style' => 'width: 27%;',
                ],
            ],
        ],
    ]); ?>
</div>
