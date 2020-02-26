<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use backend\components\widgets\GridView\GridViewWidget as GridView;
use backend\components\widgets\HeaderMenu\HeaderMenu;
use common\models\Currency;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\CurrencySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Currencies');
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
                'format' => 'raw',
                'attribute' => 'code',
                'value' => function ($model) {
                    return Html::a(
                        StringHelper::truncate($model->code, 50),
                        ['currency/update', 'id' => $model->id]
                    );
                },
                'headerOptions' => [
                    'style' => 'width: 15%;',
                ],
            ],

            [
                'attribute' => 'currency_name',
                'label' => 'Name',
                'value' => function ($model) {
                    return $model->name;
                },
            ],

            [
                'attribute' => 'amount_cnt',
            ],

            [
                'attribute' => 'amount',
            ],

            [
                    'attribute' => 'base',
                    'format' => 'raw',
                    'value' => function($model) {
                        $spanClass = 'label label-danger';
                        if ($model->base) {
                            $spanClass = 'label label-success';
                        }
                        return Html::tag(
                            'span',
                            Currency::baseOptions()[$model->base],
                            [
                                'class' => $spanClass,
                            ]
                        );
                    },
                    'filterInputOptions' => ['prompt' => '<'.Yii::t('app', 'Base').'>', 'class'=> 'form-control'],
                    'filter' => Currency::baseOptions(),
                    'headerOptions' => [
                    'style' => 'width: 8%;',
                ],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{makebase} {delete}',
                'visibleButtons' => [
                    'makebase' => function ($model) {
                        return !$model->base;
                    },
                ],
                'buttons' => [
                    'makebase' => function ($url) {
                        return Html::a(join(' ', [
                                '<span class="glyphicon glyphicon-pushpin"></span>',
                                Yii::t('app', 'Make base')
                            ]),
                            $url,
                            [
                                'class' => 'btn btn-xs btn-success',
                                'style' => 'margin-bottom: 5px;',
                                'title' => Yii::t('app', 'Make base'),
                                'aria-label' => Yii::t('app', 'Make base'),
                                'data-confirm' => Yii::t('app', 'Make base').'?',
                                'data' => [
                                    'method' => 'post',
                                    'pjax' => '0',
                                ],
                            ]
                        );
                    },
                ],
                'headerOptions' => [
                    'style' => 'width: 15%;',
                ],
            ],
        ],
    ]); ?>
</div>
