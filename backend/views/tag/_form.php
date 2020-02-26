<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Tag */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tag-form box-body">

    <?php $form = ActiveForm::begin(); ?>

    <?php /*echo $form->field($model, 'language_code')->label(Yii::t('app', 'Language version'))->dropDownList(
        array_combine(
            Yii::$app->params['languagesAvailable'],
            array_map(function ($lang) {return Yii::t('app', $lang);}, Yii::$app->params['languagesAvailable'])
        )
    )*/ ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
