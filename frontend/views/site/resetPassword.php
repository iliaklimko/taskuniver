<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$title = Yii::t('app', 'ResetPasswordPage.title');
$h1 = Yii::t('app', 'ResetPasswordPage.h1');

$this->title = Html::encode($title);
$this->params['breadcrumbs'][] = $title;
?>
<div class="page-content workarea">
    <?= $this->render('//layouts/partials/breadcrumbs') ?>
    <div class="page-content__title">
        <div class="container-fluid">
            <h1><?= Html::encode($h1) ?></h1>
        </div>
    </div>
    <div class="container-fluid">
        <div class="page-content__wrapper">
            <div class="recovery">
                <?php $form = ActiveForm::begin([
                    'enableClientValidation' => false,
                    'options' => [
                        'id' => 'reset-password-form',
                    ],
                    'errorCssClass' => 'input-wrap--error',
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"input-wrap__overflow\">{input}\n{error}</div>",
                        'options' => ['class' => 'input-wrap'],
                        'errorOptions' => ['tag' => 'span', 'class' => 'label-error'],
                    ],
                ]); ?>
                    <?= $form->field($model, 'password', [
                            'template' => "{label}\n<div class=\"input-wrap__overflow\">{input}\n<a href=\"#\" class=\"pass-toggle\"></a>\n{error}</div>",
                        ])->passwordInput()->label(Yii::t('app', 'GuideSignupPage.password').': ') ?>

                    <div class="btn-wrap">
                        <button type="submit" class="btn btn--minimal"><?= Yii::t('app', 'ResetPasswordPage.save') ?></button>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
