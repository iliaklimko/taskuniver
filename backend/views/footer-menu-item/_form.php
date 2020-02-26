<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\components\widgets\ActiveForm\ActiveForm;
use yii\bootstrap\Tabs;
use common\models\FooterMenuItem;

/* @var $this yii\web\View */
/* @var $model common\models\City */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="city-form box-body">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'column')->dropDownList(
        FooterMenuItem::getColumnList(),
        ['prompt' => '<' . Yii::t('app', 'Column') . '>']
    ) ?>

    <?php $tabsItems = array_map(function ($lang) use ($model, $form){
        return [
            'label' => Yii::t('app', $lang),
            'content' => $form->field($model->translate($lang), "[$lang]title")->textInput()
                       . $form->field($model->translate($lang), "[$lang]url")->textInput(),
        ];
    }, Yii::$app->params['languagesAvailable']); ?>

    <?= Tabs::widget(['items' => $tabsItems]) ?>

    <?= $form->field($model, "position")->label('Priority')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
