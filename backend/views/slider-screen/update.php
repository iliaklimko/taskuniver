<?php

use yii\helpers\Html;
use backend\components\widgets\HeaderMenu\HeaderMenu;
use backend\components\widgets\ActiveForm\ActiveForm;
use backend\components\widgets\FileInput\FileInputWidget as FileInput;

$pluginOptions = [];
if (!is_null($model->getUploadUrl('image'))) {
    $pluginOptions = [
        'initialPreview' => [
            Html::img(
                $model->getUploadUrl('image'), [
                    'class' => 'file-preview-picture img-responsive',
                    'style' => 'height: 160px;',
            ]),
        ],
        'initialCaption' => $model->image,
    ];
}

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Slider Screen',
]) . $model->alias;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Slider'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->alias, 'url' => ''];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<?php echo HeaderMenu::widget([
        'rightItems' => [
            ['label' => '<i class="fa fa-times-circle"></i>' . ' ' . Yii::t('app', 'Close'), 'url' => ['index']],
        ],
])  ?>

<div class="city-update box box-primary">

    <div class="city-form box-body">

    <?php $form = ActiveForm::begin(['id' => 'premium-block-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'alias')->textInput(['readonly' => true]) ?>

    <?= $form->field($model, 'image')->widget(FileInput::className(), [
        'pluginOptions' => $pluginOptions,
        'options' => [
            'data-model-class' => $model->className(),
            'data-model-id' => $model->id,
            'data-model-attribute' => 'image',
        ],
    ])->hint('File size cannot exceed 5 Mb, Dimensions 1600x846') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    </div>

</div>
