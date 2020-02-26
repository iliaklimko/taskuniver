<?php

use yii\widgets\ActiveForm;
?>

<div id="publication" class="dnone">
    <div class="popup">
        <div class="popup__title"><?= Yii::t('app', 'SubmitPostPopup.title') ?></div>
        <div class="popup__form">
            <?php $form = ActiveForm::begin([
                'options' => ['enctype' => 'multipart/form-data'],
                'errorCssClass' => 'input-wrap--error',
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"error-wrap\">{input}\n{error}</div>",
                    'options' => ['class' => 'input-wrap'],
                    'errorOptions' => ['tag' => 'span', 'class' => 'label-error'],
                ],
            ]); ?>
                <?= $form->field($submitPostForm, 'name')->textInput()->label(Yii::t('app', 'SubmitPostPopup.name').' <sup>*</sup>') ?>
                <?= $form->field($submitPostForm, 'email')->textInput()->label(Yii::t('app', 'SubmitPostPopup.email').' <sup>*</sup>') ?>
                <?= $form->field($submitPostForm, 'body')->textarea()->label(Yii::t('app', 'SubmitPostPopup.body').' <sup>*</sup>') ?>

                <div class="input-wrap">
                    <label><?= Yii::t('app', 'SubmitPostPopup.attach') ?>:</label>
                    <div class="uploadbutton" data-model-name="SubmitPostForm[attachment]">
                        <input class="input-file"
                            type="file"
                            name="SubmitPostForm[attachment]"
                            data-label="<?= Yii::t('app', 'SubmitPostPopup.inputHint') ?>"
                        />
                        <div class="button" ><?= Yii::t('app', 'SubmitPostPopup.input') ?><span><?= Yii::t('app', 'SubmitPostPopup.inputHint') ?></span></div>
                        <div class='input-file-text'>&nbsp;</div>
                    </div>
                </div>
                <div class="input-wrap">
                    <label>&nbsp;</label>
                    <input type="submit" value="<?= Yii::t('app', 'SubmitPostPopup.submitButton') ?>">
                    <a href="#success-msg" class="btn fb-inline" style="display: none;"><?= Yii::t('app', 'SubmitPostPopup.submitButton') ?></a>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
