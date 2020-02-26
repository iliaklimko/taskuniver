<?php

use yii\helpers\Html;
use backend\components\widgets\ActiveForm\ActiveForm;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model common\models\Currency */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="currency-form box-body">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'code')->textInput([
        'readonly' => !$model->isNewRecord
    ]) ?>

    <?php /*echo $form->field($model, 'base')->checkbox();*/ ?>

    <?= $form->field($model, 'amount_cnt')->textInput() ?>

    <?= $form->field($model, 'amount')->textInput() ?>

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

    <div class="well">
        <p>
        <i class="fa fa-info-circle fa-4x fa-pull-left"></i>
           <?= nl2br(Yii::t('app', 'Currency reference')) ?>
        </p>
    </div>

</div>
