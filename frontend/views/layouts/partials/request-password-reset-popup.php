<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$model = $this->params['passwordResetRequestForm'];
?>

<div id="recovery-password" class="dnone">
    <div class="popup">
        <div class="popup__title"><?= Yii::t('app', 'RequestPasswordResetPopup.title') ?></div>
        <div class="popup__form">
            <?php $form = ActiveForm::begin([
                'id' => 'recovery-form',
                'action' => ['site/request-password-reset', 'locale' => Yii::$app->request->get('locale')],
                'enableAjaxValidation' => true,
                'validationUrl' => ['site/validate-request-password-reset-form', 'locale' => Yii::$app->request->get('locale')],
                'errorCssClass' => 'input-wrap--error',
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"error-wrap\">{input}\n{error}</div>",
                    'options' => ['class' => 'input-wrap'],
                    'errorOptions' => ['tag' => 'span', 'class' => 'label-error'],
                ],
            ]); ?>
                <?= $form->field($model, 'email')->label(Yii::t('app', 'RequestPasswordResetPopup.email').':')->textInput(['id' => 'email-field-recovery-form']) ?>
                <div id="recovery-btn-wrap" class="input-wrap">
                    <label>&nbsp;</label>
                    <button id="recovery-btn" type="submit" class="btn"><?= Yii::t('app', 'RequestPasswordResetPopup.resetButton') ?></button>
                </div>
                <div id="confirm-retry-wrap" class="input-wrap dnone">
                    <p class="pre-confirm-message"><sup>*</sup> <?= Yii::t('app', 'SignupPopup.preConfirmMessage') ?></p>
                    <label>&nbsp;</label>
                    <button id="confirm-retry-recovery-form" data-email="" data-url="<?= Url::to(['/site/confirm-retry']) ?>" class="btn"><?= Yii::t('app', 'SignupPopup.remindButton') ?></button>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
