<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use backend\components\widgets\GridView\GridViewWidget as GridView;
use backend\components\widgets\HeaderMenu\HeaderMenu;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\LanguageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Languages');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo HeaderMenu::widget([
        'rightItems' => [
            ['label' => '<i class="fa fa-plus-circle"></i>' . ' ' . Yii::t('app', 'Add New'), 'url' => ['create']],
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
                'format' => 'raw',
                'label' => 'Name',
                'attribute' => 'language_name',
                'value' => function ($model) {
                    return Html::a(
                        StringHelper::truncate($model->name, 50),
                        ['language/update', 'id' => $model->id]
                    );
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
