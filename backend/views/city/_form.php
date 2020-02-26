<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\components\widgets\ActiveForm\ActiveForm;
use yii\bootstrap\Tabs;
use kartik\file\FileInput;

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

/* @var $this yii\web\View */
/* @var $model common\models\City */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="city-form box-body">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?php /*echo $form->field($model, 'image')->widget(FileInput::className(), [
        'pluginOptions' => $pluginOptions,
    ]);*/ ?>

    <?= $form->field($model, 'country_id')->label(Yii::t('app', 'Country'))->dropDownList(
        ArrayHelper::map($countryList, 'id', function ($country) {
            return $country->translate('en')->name;
        }),
        ['prompt' => '<' . Yii::t('app', 'Country') . '>']
    ) ?>

    <?php $tabsItems = array_map(function ($lang) use ($model, $form){
        return [
            'label' => Yii::t('app', $lang),
            'content' => $form->field($model->translate($lang), "[$lang]name")->textInput(),
        ];
    }, Yii::$app->params['languagesAvailable']); ?>

    <?= Tabs::widget(['items' => $tabsItems]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
