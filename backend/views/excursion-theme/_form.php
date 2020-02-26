<?php

use yii\helpers\Html;
use backend\components\widgets\ActiveForm\ActiveForm;
use yii\bootstrap\Tabs;
use common\models\ExcursionTheme;

/* @var $this yii\web\View */
/* @var $model common\models\ExcursionTheme */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="language-form box-body">

    <?php $form = ActiveForm::begin(); ?>

    <?php $tabsItems = array_map(function ($lang) use ($model, $form){
        return [
            'label' => Yii::t('app', $lang),
            'content' => $form->field($model->translate($lang), "[$lang]name")->textInput(),
        ];
    }, Yii::$app->params['languagesAvailable']); ?>

    <?= Tabs::widget(['items' => $tabsItems]) ?>

    <div class="hidden">
    <?= $form->field($model, 'priority')->textInput(['type' => 'number']) ?>
    </div>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
