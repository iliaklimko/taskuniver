<?php

/* @var $this yii\web\View */
/* @var $form \yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\View;
use yii\widgets\ActiveForm;
use frontend\assets\AvatarChangeAsset;

$this->registerJs(<<<'JS'
    $("#user-interface_language").change(function () {
        var $fullNameEnInput = $("#user-full_name_en");
        if ($(this).val() == "ru") {
            $fullNameEnInput.parent().parent().show();
        } else {
           $fullNameEnInput.parent().parent().hide();
        }
    });
JS
);

AvatarChangeAsset::register($this);

$title = Yii::t('app', 'GuideSignupPage.title');
$h1 = $title;

$this->title = Html::encode($title);
$this->params['breadcrumbs'][] = $h1;
?>
<div class="page-content workarea">
    <?= $this->render('//layouts/partials/breadcrumbs') ?>
    <div class="page-content__title">
        <div class="container-fluid">
            <h1><?= Html::encode($h1) ?></h1>
        </div>
    </div>

    <div class="container-fluid">
        <div class="registration">
            <?php $form = ActiveForm::begin([
                'enableClientValidation' => false,
                'options' => [
                    'enctype' => 'multipart/form-data',
                    'id' => 'form-signup',
                ],
                'errorCssClass' => 'input-wrap--error',
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"input-wrap__overflow\">{input}{hint}\n{error}</div>",
                    'options' => ['class' => 'input-wrap error-wrap'],
                    'errorOptions' => ['tag' => 'span', 'class' => 'label-error'],
                    'hintOptions' => ['tag' => 'div', 'class' => 'input-wrap__overflow-msg'],
                ],
            ]); ?>
            <h2><?= Yii::t('app', 'GuideEditProfilePage.personalInfo') ?></h2>
            <?= $this->render('//user/partials/edit-profile/interface-language-select', [
                'model' => $model,
            ]) ?>
            <?= $form->field($model, 'full_name')->textInput(['maxlength' => true])->label($model->getAttributeLabel('full_name').': <sup>*</sup>') ?>
            <?= $form->field($model, 'full_name_en')->textInput(['maxlength' => true, 'id' => 'user-full_name_en'])->label($model->getAttributeLabel('full_name_en'))->hint(Yii::t('app', 'User.fullname_enHint')) ?>
            <?= $form->field($model, 'email')->hint($model->isNewRecord ? Yii::t('app', 'GuideSignupPage.emailHint') : null)->label($model->getAttributeLabel('email').': <sup>*</sup>') ?>
            <?= $form->field($model, 'phone')->textInput()->label($model->getAttributeLabel('phone').': <sup>*</sup>') ?>
            <?= $form->field($model, 'plain_password', [
                'template' => "{label}\n<div class=\"input-wrap__overflow\">{input}\n<a href=\"#\" class=\"pass-toggle\"></a>\n{hint}\n{error}</div>",
            ])->label(Yii::t('app', 'GuideSignupPage.password').': <sup>*</sup>')->passwordInput()->hint(Yii::t('app', 'GuideSignupPage.passwordHint')) ?>

            <h2><?= Yii::t('app', 'GuideEditProfilePage.geography') ?></h2>
            <?= $this->render('//user/partials/edit-profile/city-select', [
                'model' => $model,
                'cityList' => $cityList,
            ]) ?>
            <?= $this->render('//user/partials/edit-profile/language-select', [
                'model' => $model,
                'languageList' => $languageList,
            ]) ?>

            <h2><?= Yii::t('app', 'GuideEditProfilePage.additionalInfo') ?></h2>
            <?= $form->field($model, 'bio')->textarea(['rows' => 6])->label($model->getAttributeLabel('bio').': <sup>*</sup>') ?>
            <div class="input-wrap">
                <label>&nbsp;</label>
                <div class="input-wrap__overflow">
                    <div class="checkbox-wrap">
                        <input type="hidden" name="<?= $model->formName() ?>[instant_confirmation]" value="0">
                        <input type="checkbox"
                               id="confirmation"
                               name="<?= $model->formName() ?>[instant_confirmation]"
                               value="1"
                            <?= $model->instant_confirmation ? 'checked' : null ?>
                        >
                        <label for="confirmation">
                            <?= Yii::t('app', 'GuideEditProfilePage.instantConfirmation') ?>
                        </label>
                    </div>
                </div>
            </div>
            <div class="input-wrap">
                <label><?= $model->getAttributeLabel('avatar') ?>:</label>
                <input type="hidden" name="<?= $model->formName() ?>[avatar]" value="" />
                <div class="avatar-change"
                     data-model-name="<?= $model->formName() ?>[avatar]"
                     data-model-id="<?= $model->id ?>"
                >
                    <div class="uploadbutton" data-model-name="<?= $model->formName() ?>[avatar]">
                        <input class="input-file"
                               type="file"
                               name="<?= $model->formName() ?>[avatar]"
                               accept="image/*"
                        />
                        <div class="button" ><?= Yii::t('app', 'GuideEditProfilePage.chooseFile') ?></div>
                        <div class='input-file-text'>&nbsp;</div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="input-wrap">
                <label>&nbsp;</label>
                <div class="input-wrap__overflow">
                    <div class="checkbox-wrap">
                        <input type="hidden" name="<?= $model->formName() ?>[agree]" value="0">
                        <input type="checkbox"
                               id="agreement"
                               name="<?= $model->formName() ?>[agree]"
                               value="1"
                            <?= $model->agree ? 'checked' : null ?>
                        >
                        <label for="agreement">
                            <?= Yii::t('app', 'GuideSignupPage.agreement {url}', [
                                'url' => Url::to(['static-page/view', 'page_alias' => 'rules', 'locale' => Yii::$app->language !== 'ru' ? Yii::$app->language : null]),
                            ]) ?>
                        </label>
                        <?= Html::error($model, 'agree', ['tag' => 'span', 'class' => 'label-error']) ?>
                    </div>
                </div>
            </div>
            <h2><?= Yii::t('app', 'ExcursionViewPage.detailsForPayment') ?></h2>
            <?= $form->field($model, 'INN')->textInput(['onkeydown' => 'javascript: return ((event.keyCode>47)&&(event.keyCode<58)||(event.keyCode==8)||((event.keyCode>95)&&(event.keyCode<106)))'])->label(Yii::t('app','ExcursionViewPage.inn')) ?>
            <?= $form->field($model, 'OGRN')->textInput(['onkeydown' => 'javascript: return ((event.keyCode>47)&&(event.keyCode<58)||(event.keyCode==8)||((event.keyCode>95)&&(event.keyCode<106)))'])->label(Yii::t('app','ExcursionViewPage.ogr')) ?>
            <?= $form->field($model, 'number_card')->textInput()->label(Yii::t('app','ExcursionViewPage.numberCard')) ?>
            <?= $form->field($model, 'bik')->textInput()->label(Yii::t('app','ExcursionViewPage.bik')) ?>
            <?= $form->field($model, 'comment')->textarea(['rows' => 6])->label(Yii::t('app','ExcursionViewPage.comment')) ?>

            <div class="btn-wrap">
                <p class="must-fill-fields"><?= Yii::t('app', 'GuideEditProfilePage.mustFillFields') ?></p>
                <?= Html::submitButton(Yii::t('app', 'GuideSignupPage.signupButton'), [
                    'class' => 'btn btn--minimal',
                    'name' => 'signup-button'
                ]) ?>
                <a href="/" class="btn fb-inline" style="display: none;"></a>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
