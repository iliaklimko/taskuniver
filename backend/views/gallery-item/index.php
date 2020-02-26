<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\StringHelper;
use backend\components\widgets\GridView\GridViewWidget as GridView;
use backend\components\widgets\HeaderMenu\HeaderMenu;
use backend\components\widgets\ActiveForm\ActiveForm;
use kartik\file\FileInput;

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

$this->title = join(' | ', [$searchModel->post->title, Yii::t('app', 'Gallery')]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Posts'), 'url' => ['post/index']];
$this->params['breadcrumbs'][] = ['label' => $searchModel->post->title, 'url' => ['post/update', 'id' => $searchModel->post->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Gallery');

/* @var $this yii\web\View */
/* @var $searchModel backend\models\search\GalleryItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<?php echo HeaderMenu::widget([
    'brandLabel' => Yii::t('app', 'Edit') . ' ' . StringHelper::truncate($searchModel->post->title, 20),
    'rightItems' => [
        ['label' => '<i class="fa fa-times-circle"></i>' . ' ' . Yii::t('app', 'Close'), 'url' => ['post/update', 'id' => $searchModel->post->id]],
    ],
])  ?>

<div class="gallery-item-index box box-primary">

    <div class="box-header with-border">
        <h3 class="box-title"><?= Yii::t('app', 'Gallery') ?></h3>
    </div>

    <div class="box-body">

        <div class="well">
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
            <?= $form->field($uploadForm, 'files[]')->widget(FileInput::classname(), [
                'options' => [
                    'multiple' => true,
                    'accept' => 'image/*',
                ]
            ]) ?>
            <button class="btn btn-primary"><?= Yii::t('app', 'Upload') ?></button>
            <?php ActiveForm::end() ?>
        </div>

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

                'filename',

                [
                    'label' => Yii::t('app', 'Image'),
                    'format' => 'html',
                    'value' => function ($model) {
                        return Html::img($model->getUploadUrl('filename'), [
                            'style' => 'height: 100px;'
                        ]);
                    }
                ],

                [
                    'attribute' => 'created_at',
                    'format' => ['date', 'php:Y-m-d H:i'],
                    'filter' => false,
                ],

                [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'urlCreator' => $urlCreator($searchModel->post_id),
                'headerOptions' => [
                    'style' => 'width: 15%;',
                ],
            ],
            ],
        ]); ?>

    </div>
</div>
