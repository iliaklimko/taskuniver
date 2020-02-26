<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ProfileForm */

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use frontend\components\widgets\OfficeMenu\OfficeMenuWidget as OfficeMenu;
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

$title = Yii::t('app', 'GuideEditProfilePage.title');
$h1 = Yii::t('app', 'GuideEditProfilePage.h1');

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
        <div class="page-content__wrapper">
            <div class="cabinet">
                <?= OfficeMenu::widget([
                    'showListExcursions' => $this->params['showListExcursions'],
                    'showListOrders'     => $this->params['showListOrders'],
                    'newOrdersCount'     => $this->params['newOrdersCount'],
                ]) ?>
                <div class="cabinet__data">
                    <div class="registration">
                        <?php $form = ActiveForm::begin([
                            'enableClientValidation' => false,
                            'options' => [
                                'enctype' => 'multipart/form-data',
                                'id' => 'form-edit-profile',
                            ],
                            'errorCssClass' => 'input-wrap--error',
                            'fieldConfig' => [
                                'template' => "{label}\n<div class=\"input-wrap__overflow\">{input}\n{error}</div>",
                                'options' => ['class' => 'input-wrap'],
                                'errorOptions' => ['tag' => 'span', 'class' => 'label-error'],
                            ],
                        ]); ?>
                        <h2><?= Yii::t('app', 'GuideEditProfilePage.personalInfo') ?></h2>
                        <?= $this->render('//user/partials/edit-profile/interface-language-select', [
                            'model' => $model,
                        ]) ?>
                        <?= $form->field($model, 'full_name')->textInput(['maxlength' => true])->label($model->getAttributeLabel('full_name').': <sup>*</sup>') ?>
                        <?= $form->field($model, 'full_name_en')->textInput(['maxlength' => true, 'id' => 'user-full_name_en'])->label($model->getAttributeLabel('full_name_en').':')->hint(Yii::t('app', 'User.fullname_enHint')) ?>
                        <?= $form->field($model, 'email')->label($model->getAttributeLabel('email').': <sup>*</sup>') ?>
                        <?= $form->field($model, 'phone')->textInput()->label($model->getAttributeLabel('phone').': <sup>*</sup>') ?>
                        <?= $form->field($model, 'plain_password_new', [
                            'template' => "{label}\n<div class=\"input-wrap__overflow\">{input}\n<a href=\"#\" class=\"pass-toggle\"></a>\n{error}</div>",
                        ])->passwordInput()->label(Yii::t('app', 'GuideEditProfilePage.newPassword').': <sup>*</sup>') ?>

                        <h2><?= Yii::t('app', 'GuideEditProfilePage.geography') ?></h2>
                        <?= $this->render('partials/edit-profile/city-select', [
                            'model' => $model,
                            'cityList' => $cityList,
                        ]) ?>
                        <?= $this->render('partials/edit-profile/language-select', [
                            'model' => $model,
                            'languageList' => $languageList,
                        ]) ?>

                        <h2><?= Yii::t('app', 'GuideEditProfilePage.socialLinks') ?></h2>
                        <?= $form->field($model, 'account_vkontakte')->label($model->getAttributeLabel('account_vkontakte').':') ?>
                        <?= $form->field($model, 'account_facebook')->label($model->getAttributeLabel('account_facebook').':') ?>
                        <?= $form->field($model, 'account_instagram')->label($model->getAttributeLabel('account_instagram').':') ?>
                        <?= $form->field($model, 'account_twitter')->label($model->getAttributeLabel('account_twitter').':') ?>

                        <h2><?= Yii::t('app', 'GuideEditProfilePage.additionalInfo') ?></h2>
                        <?= $form->field($model, 'bio')->textarea(['rows' => 6])->label($model->getAttributeLabel('bio').': <sup>*</sup>') ?>
                        <div class="input-wrap">
                            <label>&nbsp;</label>
                            <div class="input-wrap__overflow">
                                <div class="checkbox-wrap">
                                    <input type="hidden" name="ProfileForm[instant_confirmation]" value="0">
                                    <input type="checkbox"
                                        id="confirmation"
                                        name="ProfileForm[instant_confirmation]"
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
                            <label>&nbsp;</label>
                            <div class="input-wrap__overflow">
                                <div class="checkbox-wrap">
                                    <input type="hidden" name="ProfileForm[can_paid_by_card]" value="0">
                                    <input type="checkbox"
                                        id="can_paid_by_card"
                                        name="ProfileForm[can_paid_by_card]"
                                        value="1"
                                        <?= $model->can_paid_by_card ? 'checked' : null ?>
                                    >
                                    <label for="can_paid_by_card">
                                        <?= Yii::t('app', 'GuideEditProfilePage.canPaidByCard') ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="input-wrap">
                            <label>&nbsp;</label>
                            <div class="input-wrap__overflow">
                                <div class="checkbox-wrap">
                                    <input type="hidden" name="ProfileForm[pay_cash]" value="0">
                                    <input type="checkbox"
                                           id="pay_cash"
                                           name="ProfileForm[pay_cash]"
                                           value="1"
                                        <?= $model->pay_cash ? 'checked' : null ?>
                                    >
                                    <label for="pay_cash">
                                        <?= Yii::t('app', 'GuideEditProfilePage.cash_payment') ?>
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
                                <?php if ($model->getUploadUrl('avatar')): ?>
                                <div class="user-img-wrap">
                                    <div class="user-img">
                                        <span class="user-img__close"
                                            id="avatar-preview-remove"
                                            data-model-id="<?= $model->id ?>"
                                        ></span>
                                        <div class="user-img__wrapper avatar-preview"
                                            id="avatar-preview"
                                            style="background:url(<?= $model->getUploadUrl('avatar') ?: null ?>);"
                                        >
                                        </div>
                                    </div>
                                    <a href="#" class="user-img__change">
                                        <?= Yii::t('app', 'GuideEditProfilePage.chooseFileAnother') ?>
                                        <input type='file' class="imgInp"
                                            name="<?= $model->formName() ?>[avatar]"
                                            id="avatar-input"
                                            accept="image/*"
                                        />
                                    </a>
                                </div>
                                <?php else: ?>
                                <div class="uploadbutton"
                                     data-model-name="<?= $model->formName() ?>[avatar]"
                                >
                                    <input class="input-file"
                                        type="file"
                                        name="<?= $model->formName() ?>[avatar]"
                                        accept="image/*"
                                    />
                                    <div class="button" ><?= Yii::t('app', 'GuideEditProfilePage.chooseFile') ?></div>
                                    <div class='input-file-text'>&nbsp;</div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <hr>
                        <div class="btn-wrap">
                            <p class="must-fill-fields"><?= Yii::t('app', 'GuideEditProfilePage.mustFillFields') ?></p>
                            <?= Html::submitButton(Yii::t('app', 'GuideEditProfilePage.saveButton'), [
                                'class' => 'btn btn--minimal',
                                'name' => 'signup-button'
                            ]) ?>
                            <a href="#success-msg" class="btn fb-inline" style="display: none;"></a>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->render('partials/edit-profile/success-submit-popup') ?>
