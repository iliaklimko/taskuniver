<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\components\helpers\BaseHelper;
use yii\widgets\ActiveForm;

$routes = [
    'excursion/view',
];
$returnTo = in_array(Yii::$app->controller->route, $routes)
    ? Url::to(['/user/create-excursion', 'locale' => Yii::$app->request->get('locale')])
    : null;

$model = $this->params['loginForm'];

$resetPassword = '<a href="#recovery-password" class="forgot fb-inline">'.Yii::t('app', 'SignupPopup.forgot').'?'.'</a>';
?>

<div id="guide-enter" class="dnone">
    <div class="popup">
        <div class="popup__title"><?= Yii::t('app', 'SignupPopup.title') ?></div>
        <div class="popup__form">
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'action' => ['site/login', 'locale' => Yii::$app->request->get('locale')],
                'enableAjaxValidation' => true,
                'validationUrl' => ['site/validate-login-form', 'locale' => Yii::$app->request->get('locale')],
                'errorCssClass' => 'input-wrap--error',
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"error-wrap\">{input}\n{error}</div>",
                    'options' => ['class' => 'input-wrap'],
                    'errorOptions' => ['tag' => 'span', 'class' => 'label-error'],
                ],
            ]); ?>
                <input type="hidden" name="returnTo" value="<?= $returnTo ?>" />
                <?= $form->field($model, 'username')->label(Yii::t('app', 'SignupPopup.username').':')->textInput(['id' => 'username-field-login-form']) ?>
                <div id="confirm-wrap-hide">
                <?= $form->field($model, 'password', [
                    'template' => "{label}\n<div class=\"error-wrap\">{input}\n{$resetPassword}\n{error}</div>"
                ])->label(Yii::t('app', 'SignupPopup.password').':')->passwordInput(['id' => 'password-field-login-form']) ?>
                <div class="input-wrap">
                    <label>&nbsp;</label>
                    <div class="btn-wrap">
                        <button type="submit" class="btn"><?= Yii::t('app', 'SignupPopup.loginButton') ?></button>
                        <a href="<?= Url::to(['/site/signup', 'locale' => Yii::$app->request->get('locale')]) ?>" class="popup-register"><?= Yii::t('app', 'SignupPopup.register') ?></a>
                    </div>
                </div>
                </div>
                <div id="confirm-wrap-show" class="input-wrap dnone">
                    <label>&nbsp;</label>
                    <div class="btn-wrap">
                        <p class="pre-confirm-message"><sup>*</sup> <?= Yii::t('app', 'SignupPopup.preConfirmMessage') ?></p>
                        <button id="confirm-retry-login-form" data-email="" data-url="<?= Url::to(['/site/confirm-retry', 'locale' => Yii::$app->request->get('locale')]) ?>" class="btn"><?= Yii::t('app', 'SignupPopup.remindButton') ?></button>
                    </div>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
