<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use backend\components\widgets\GridView\GridViewWidget as GridView;
use backend\components\widgets\HeaderMenu\HeaderMenu;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\StaticPageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Static pages');
$this->params['breadcrumbs'][] = Yii::t('app', 'Static pages');
?>

<?php echo HeaderMenu::widget([
        'rightItems' => [
            ['label' => '<i class="fa fa-plus-circle"></i>' . ' ' . Yii::t('app', 'Add New'), 'url' => ['create']],
        ],
])  ?>

<div class="post-index box box-primary">
    <div class="box-body">
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
                    'format' => 'raw',
                    'attribute' => 'static_page_title',
                    'label' => 'Title (ENG)',
                    'value' => function ($model) {
                        return Html::a(
                            StringHelper::truncate($model->title, 50),
                            ['static-page/update', 'id' => $model->id]
                        );
                    },
                ],

                [
                    'format' => 'raw',
                    'label' => 'Title (RUS)',
                    'value' => function ($model) {
                        return Html::a(
                            StringHelper::truncate($model->translate('ru')->title, 50),
                            ['static-page/update', 'id' => $model->id]
                        );
                    },
                ],

                [
                    'label' => 'URL (ENG)',
                    'value' => function ($model) {
                        $url = Yii::$app->urlManagerFrontend->createAbsoluteUrl([
                            'static-page/view',
                            'locale' => 'en',
                            'page_alias' => $model->url_alias,
                        ]);
                        return $url;
                    },
                ],

                [
                    'label' => 'URL (RUS)',
                    'value' => function ($model) {
                        $url = Yii::$app->urlManagerFrontend->createAbsoluteUrl([
                            'static-page/view',
                            'page_alias' => $model->url_alias,
                        ]);
                        return $url;
                    },
                ],

                [
                    'label' => Yii::t('app', 'View on site'),
                    'format' => 'raw',
                    'value' => function ($model) {
                        $url = Yii::$app->urlManagerFrontend->createAbsoluteUrl([
                            'static-page/view',
                            'page_alias' => $model->url_alias,
                        ]);
                        return Html::a(
                            '<i class="fa fa-external-link"></i>',
                            $url,
                            ['target' => '_blank']
                        );
                    },
                    'headerOptions' => [
                        'style' => 'width: 3%;',
                    ],
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
</div>
