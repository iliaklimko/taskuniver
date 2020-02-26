<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\components\widgets\ActiveForm\ActiveForm;
use yii\bootstrap\Tabs;

/* @var $this yii\web\View */
/* @var $model common\models\Sight */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="currency-form box-body">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'city_id')->label(Yii::t('app', 'City'))->dropDownList(
        ArrayHelper::map($cityList, 'id', function ($city) {
            return $city->translate('en')->name;
        }),
        ['prompt' => '<' . Yii::t('app', 'City') . '>']
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
