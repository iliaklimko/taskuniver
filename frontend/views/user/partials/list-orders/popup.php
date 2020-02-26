<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div id="confirmation" class="dnone">
    <div class="popup popup--application">
        <div class="popup__title"><?= Yii::t('app', 'OrderConfirmationPopup.title') ?></div>
        <div class="p-applicaation-msg"><?= Yii::t('app', 'OrderConfirmationPopup.hint') ?></div>
        <div class="popup__form">
            <?php $formConfirmation = ActiveForm::begin([
                'id' => 'confirmation-form',
                'options' => ['class' => 'popup__form'],
                'errorCssClass' => 'input-wrap--error',
                'fieldConfig' => [
                    'template' => "<div class=\"error-wrap\">{input}\n{error}</div>",
                    'options' => ['class' => 'input-wrap'],
                    'errorOptions' => ['tag' => 'span', 'class' => 'label-error'],
                ],
            ]); ?>
                <input type="hidden" id="order-id-confirmation-form" name="<?= $confirmation->formName() ?>[order_id]" value="">
                <?= $formConfirmation->field($confirmation, 'message')->textarea() ?>
                <div class="popup__excursion-payment">
                    <input class="btn" type="submit" value="<?= Yii::t('app', 'OrderConfirmationPopup.sendButton') ?>">
                    <span class="popup__excursion-redirect">
                        <?= Yii::t('app', 'OrderConfirmationPopup.sendButtonHint') ?>
                    </span>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<div id="renouncement" class="dnone">
    <div class="popup popup--application">
        <div class="popup__title"><?= Yii::t('app', 'OrderRenouncementPopup.title') ?></div>
        <div class="popup__form">
            <?php $formRenouncement = ActiveForm::begin([
                'id' => 'renouncement-form',
                'options' => ['class' => 'popup__form'],
                'errorCssClass' => 'input-wrap--error',
                'fieldConfig' => [
                    'template' => "<div class=\"error-wrap\">{input}\n{error}</div>",
                    'options' => ['class' => 'input-wrap'],
                    'errorOptions' => ['tag' => 'span', 'class' => 'label-error'],
                ],
            ]); ?>
                <input type="hidden" id="order-id-renouncement-form" name="<?= $renouncement->formName() ?>[order_id]" value="">
                <?= $formRenouncement->field($renouncement, 'message')->textarea() ?>
                <div class="popup__excursion-payment">
                    <input class="btn" type="submit" value="<?= Yii::t('app', 'OrderRenouncementPopup.sendButton') ?>">
                    <span class="popup__excursion-redirect">
                        <?= Yii::t('app', 'OrderRenouncementPopup.sendButtonHint') ?>
                    </span>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
