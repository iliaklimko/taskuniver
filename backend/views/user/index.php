<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use backend\components\widgets\GridView\GridViewWidget as GridView;
use backend\components\widgets\HeaderMenu\HeaderMenu;

$concatItems = function ($items) {
    return nl2br(join(", \n", array_map(function ($item) {
        return $item->name;
    }, $items)));
};

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo HeaderMenu::widget([
        'rightItems' => [
            ['label' => '<i class="fa fa-plus-circle"></i>' . ' ' . Yii::t('app', 'Add New'), 'url' => ['create']],
        ],
])  ?>

<div class="user-index box box-primary">

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

            'email',

            [
                'format' => 'raw',
                'attribute' => 'full_name',
                'value' => function ($model) {
                    return Html::a(
                        StringHelper::truncate($model->full_name, 50),
                        ['user/update', 'id' => $model->id]
                    );
                },
            ],

            [
                'format' => 'raw',
                'attribute' => 'user_city_name',
                'value' => function ($model) use ($concatItems) {
                    return $concatItems($model->cities);
                },
            ],

            // [
            //     'attribute' => 'user_group_id',
            //     'value' => function ($model) {
            //         return $model->group ? $model->group->name : null;
            //     },
            //     'filter' => ArrayHelper::map($userGroupList, 'id', 'name'),
            // ],

            'phone',

            [
                'label' => Yii::t('app', 'Avatar'),
                'format' => 'html',
                'value' => function ($model) {
                    return Html::img($model->getUploadUrl('avatar'), [
                        'style' => 'height: 50px;'
                    ]);
                }
            ],

            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d H:i'],
                'filter' => false,
            ],

            [
                'attribute' => 'updated_at',
                'format' => ['date', 'php:Y-m-d H:i'],
                'filter' => false,
            ],

            [
                'label' => 'List excursions',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a(
                        '<i class="fa fa-list"></i> Excursions ('.count($model->excursions).')',
                        ['excursion/index', 'ExcursionSearch[excursion_user]' => $model->full_name]
                    );
                },
            ],

            // [
            //     'label' => Yii::t('app', 'Profile'),
            //     'format' => 'raw',
            //     'value' => function ($model) {
            //         $url = Yii::$app->urlManagerFrontend->createAbsoluteUrl([
            //             'user/view-profile',
            //             'id' => $model->id
            //         ]);
            //         return Html::a(
            //             '<i class="fa fa-external-link"></i>',
            //             $url,
            //             ['target' => '_blank']
            //         );
            //     },
            // ],

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
