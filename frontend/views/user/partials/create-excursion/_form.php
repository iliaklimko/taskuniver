<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use frontend\assets\FeaturedImageChangeAsset;
use common\models\translations\ExcursionTranslation;



if ($model->hasTranslation('en') && $model->hasTranslation('ru')) {
    $showForTranslate = $model->user->interface_language != $lang;
} else {
$showForTranslate = $lang == 'ru'
    ? $model->hasTranslation('en')
    : $model->hasTranslation('ru');
}

if(isset($excursion->visitors) || !empty($model->visitors)) {
    $valVisitors = $model->visitors;
} else {
    $valVisitors = 0;
}

FeaturedImageChangeAsset::register($this);
?>
<div class="container-fluid">
    <div class="page-content__wrapper">
        <div class="create">
            <?php $form = ActiveForm::begin([
                'enableClientValidation' => false,
                'options' => [
                    'enctype' => 'multipart/form-data',
                    'id' => 'form-create-excursion',
                ],
                'errorCssClass' => 'input-wrap--error',
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"input-wrap__overflow\">{input}\n{hint}\n{error}</div>",
                    'options' => ['class' => 'input-wrap'],
                    'errorOptions' => ['tag' => 'span', 'class' => 'label-error'],
                    'hintOptions' => ['class' => 'input-wrap__overflow-msg'],
                ],
            ]); ?>
            <h2><?= Yii::t('app', 'UpdateExcursionPage.basicInformation') ?></h2>
            <?= $form->field($model->translate($lang), "[$lang]title")->textInput(['maxlength' => true])->label(Yii::t('app', 'Excursion.title').': <sup>*</sup>') ?>
            <?= $form->field($model->translate($lang), "[$lang]description")->textarea()->label(Yii::t('app', 'Excursion.description').': <sup>*</sup>')->hint(Yii::t('app', 'UpdateExcursionPage.descriptionHint')) ?>

            <div class="input-wrap <?= $model->hasErrors('group_id') ? 'input-wrap--error' : null ?>">
                <label><span><?= Yii::t('app', 'UpdateExcursionPage.excursionGroups') ?>: <sup>*</sup></span></label>
                <div class="select-wrap input-wrap__overflow">
                    <input type="hidden" name="<?= $model->formName() ?>[group_id]" value="">
                    <select class="fs" name="<?= $model->formName() ?>[group_id]">
                        <option value=""></option>
                        <?php foreach ($excursionGroups as $groups): ?>
                            <option value="<?= $groups->id ?>"
                                <?= $groups['id'] == $model->group_id ? "selected" : ''?>
                            >
                                <?= $groups->name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php foreach ($excursionGroups as $groups): ?>

                        <input style="display: none" type="hidden" name="<?= $model->formName() ?>[group_code]" value="<?=$groups->code ?>">
                    <?php endforeach; ?>
                    <?= Html::error($model, 'group_id', ['tag' => 'span', 'class' => 'label-error']) ?>
                </div>
            </div>

            <?php if ($showForTranslate): ?><div class="dnone"><?php endif; ?>
            <div class="input-wrap">
                <label><?= Yii::t('app', 'Excursion.featuredImage') ?>: <sup>*</sup></label>
                <div class="input-wrap__overflow">
                    <input type="hidden" name="<?= $model->formName() ?>[featured_image]" value="" />
                    <div class="featured-image-change"
                         data-model-name="<?= $model->formName() ?>[featured_image]"
                         data-model-id="<?= $model->id ?>"
                    >
                    <?php if ($model->isNewRecord): ?>
                    <div class="uploadbutton" data-model-name="<?= $model->formName() ?>[featured_image]">
                        <input class="input-file"
                            type="file"
                            name="<?= $model->formName() ?>[featured_image]"
                            accept="image/*"
                        />
                        <div class="button" ><?= Yii::t('app', 'GuideEditProfilePage.chooseFile') ?></span></div>
                        <div class='input-file-text'>&nbsp;</div>
                    </div>
                    <?php else: ?>
                    <div class="gallery-img">
                        <img src="<?= $model->getUploadUrl('featured_image') ?>"
                             class="no-image blah"
                        >
                    </div>
                    <a href="#" class="gallery-img__change" style="display: block;">
                        <?= Yii::t('app', 'CreateExcursionPage.changeFile') ?>
                        <input type='file'
                               name="<?= $model->formName() ?>[featured_image]"
                               class="galleryInp"
                        />
                    </a>
                    <?php endif; ?>
                    </div>
                    <div class="input-wrap__overflow-msg">
                        <?= Yii::t('app', 'UpdateExcursionPage.featuredImageHint') ?>
                    </div>
                    <?= Html::error($model, 'featured_image', ['tag' => 'span', 'class' => 'label-error']) ?>
                </div>
            </div>
            <div class="input-wrap">
                <label><?= Yii::t('app', 'Excursion.additionalImages') ?>:</label>
                <div class="photo-list">
                    <div id="result">
                        <?php foreach ($model->images as $image): ?>
                        <div class="photo-list__item">
                            <img src="<?= $image->getUploadUrl('filename') ?>" alt="">
                            <?= Html::a(
                                '',
                                ['/user/remove-excursion-image', 'id' => $image->id],
                                [
                                    'class' => 'del',
                                    'data-image-id' => $image->id,
                                ]
                            ) ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="photo-list__add-wrap">
                        <div class="photo-list__add-item" id="add-image-wrap">
                            <a href="#" class="photo-list__add">
                                <?= $model->isNewRecord || !$model->images ? Yii::t('app', 'GuideEditProfilePage.chooseFile') : Yii::t('app', 'CreateExcursionPage.addFileMore') ?>
                            </a>
                            <input type="file"
                                id="add-image-input"
                                data-name="<?= $uploadForm->formName() ?>[files][]"
                            />
                         </div>
                    </div>
                </div>
            </div>

            <h2><?= Yii::t('app', 'UpdateExcursionPage.price') ?></h2>
            <?= $this->render('currency-dropdown', [
                'model' => $model,
                'currencyList' => $currencyList,
            ]) ?>
            <?= $form->field($model, 'current_price')->label(Yii::t('app', 'Excursion.currentPrice'))->textInput(['class' => 'price','onkeydown' => 'javascript: return ((event.keyCode>47)&&(event.keyCode<58)||(event.keyCode==8)||((event.keyCode>95)&&(event.keyCode<106)))'])->hint(Yii::t('app', 'UpdateExcursionPage.currentPriceHint')) ?>
            <?= $form->field($model, 'old_price')->label(Yii::t('app', 'Excursion.oldPrice'))->textInput(['class' => 'price','onkeydown' => 'javascript: return ((event.keyCode>47)&&(event.keyCode<58)||(event.keyCode==8)||((event.keyCode>95)&&(event.keyCode<106)))' ])->hint(Yii::t('app', 'UpdateExcursionPage.oldPriceHint')) ?>
            <?php if ($showForTranslate): ?></div><?php endif; ?>

            <?= $form->field($model->translate($lang), "[$lang]included_in_price")->textInput()->label(Yii::t('app', 'Excursion.included').': <sup>*</sup>')->hint(Yii::t('app', 'UpdateExcursionPage.includedHint')) ?>
            <?= $form->field($model->translate($lang), "[$lang]not_included_in_price")->label(Yii::t('app', 'Excursion.notIncluded').': <sup>*</sup>')->textInput()->hint(Yii::t('app', 'UpdateExcursionPage.notIncludedHint')) ?>

            <?php if ($showForTranslate): ?><div class="dnone"><?php endif; ?>
            <h2><?= Yii::t('app', 'UpdateExcursionPage.forWhom') ?></h2>
            <?= $this->render('target-audiences', [
                'model' => $model,
                'targetAudienceList' => $targetAudienceList,
            ]) ?>
                <?= $this->render('days-week', [
                    'model' => $model,
                ]) ?>
                <?= $form->field($model, "date_array")->label(Yii::t('app', 'date_array').': <sup>*</sup>')->textInput(['class' => 'price','id' => 'arrDays']) ?>

                <?= $form->field($model, 'set_to', [
                    'template' => "{label}\n<div class=\"error-wrap date-wrap\">{input}\n<a href=\"#\" class=\"date-wrap__link\"></a>\n{hint}\n{error}</div>",
                ])->textInput([
                    'placeholder' =>  $model->set_to ? $model->set_to : Yii::t('app', 'BookingForm.datePlaceholder'),
                    'id' => 'datepickerSetTo',
                    'readonly' => true,
                ])->label(Yii::t('app', 'ExcursionViewPage.setTo') , ['style' => 'padding-left:110px'])
                     ?>
                <?= $form->field($model, 'visitors')->label(Yii::t('app', 'UpdateExcursionPage.numberPlaces').':')->textInput(['class' => 'price', 'onkeydown' => 'javascript: return ((event.keyCode>47)&&(event.keyCode<58)||(event.keyCode==8)||((event.keyCode>95)&&(event.keyCode<106)))', 'value' => $valVisitors])->hint(Yii::t('app', 'UpdateExcursionPage.numberPlaces')) ?>

                <?= $this->render('days-count', [
                    'model' => $model,
                ]) ?>

                <?= $form->field($model, "time_spending")->textInput(['placeholder' => '17:00'])->label(Yii::t('app', 'ExcursionViewPage.timeSpending').'<sup>*</sup>')->hint('Формат чч:мм'); ?>

            <?php /*echo $form->field($model, 'start_time')->label('Время начала: <sup>*</sup>')->textInput(['class' => 'time-mask', 'placeholder' => '__:__'])->hint('Формат чч:мм');*/ ?>
            <?= $this->render('start-city-dropdown', [
                'model' => $model,
                'cityList' => $cityList,
            ]) ?>
            <?= $this->render('language-dropdown', [
                'model' => $model,
                'languageList' => $languageList,
            ]) ?>
            <?= $this->render('person-number-dropdown', [
                'model' => $model,
            ]) ?>
            <?= $this->render('duration-dropdown', [
                'model' => $model,
            ]) ?>
            <?= $this->render('type-dropdown.php', [
                'model' => $model,
                'excursionTypeList' => $excursionTypeList,
            ]) ?>
            <?php if ($showForTranslate): ?></div><?php endif; ?>

            <?= $form->field($model->translate($lang), "[$lang]meeting_point")->textInput()->hint(Yii::t('app', 'UpdateExcursionPage.meetingPointHint'))->label(Yii::t('app', 'UpdateExcursionPage.meetingPoint').': <sup>*</sup>') ?>

            <?php if ($showForTranslate): ?><div class="dnone"><?php endif; ?>

            <div class="input-wrap">
                <label>&nbsp;</label>
                <div class="input-wrap__overflow">
                    <div class="checkbox-wrap">
                        <input type="hidden" name="<?= $model->formName() ?>[pick_up_from_hotel]" value="0">
                        <input type="checkbox"
                            id="can"
                            name="<?= $model->formName() ?>[pick_up_from_hotel]"
                            value="1"
                            <?= $model->pick_up_from_hotel ? 'checked' : null ?>
                        >
                        <label for="can"><?= Yii::t('app', 'ExcursionViewPage.pickupFromHotel' ) ?></label>
                    </div>
                </div>
            </div>

            <h2><?= Yii::t('app', 'UpdateExcursionPage.additionalInfo') ?></h2>
            <?= $this->render('theme-dropdown.php', [
                'model' => $model,
                'excursionThemeList' => $excursionThemeList,
            ]) ?>
            <?= $this->render('city-on-the-way-dropdown.php', [
                'model' => $model,
                'cityList' => $cityList,
            ]) ?>
            <?= $form->field($model, "new_cities")->textInput(['maxlength' => true])->label(Yii::t('app', 'UpdateExcursionPage.otherCities').':')->hint(Yii::t('app', 'UpdateExcursionPage.otherCitiesHint')) ?>
            <?= $this->render('sight-on-the-way-dropdown.php', [
                'model' => $model,
                'sightList' => $sightList,
            ]) ?>
            <?= $form->field($model, "new_sights")->textInput(['maxlength' => true])->label(Yii::t('app', 'UpdateExcursionPage.otherSights').':')->hint(Yii::t('app', 'UpdateExcursionPage.otherSightsHint')) ?>
            <?php if ($showForTranslate): ?></div><?php endif; ?>

            <?= $form->field($model->translate($lang), "[$lang]additional_info")->textarea()->label(Yii::t('app', 'UpdateExcursionPage.additionalInfo').': <sup>*</sup>')->hint(Yii::t('app', 'UpdateExcursionPage.additionalInfoHint')) ?>

            <?= $form->field($model, "tagValues")->textInput(['maxlength' => true])->label(Yii::t('app', 'PostViewPage.tags').':')->hint(Yii::t('app', 'UpdateExcursionPage.tagsHint')) ?>

            <div class="btn-wrap">
                <p class="must-fill-fields"><?= Yii::t('app', 'GuideEditProfilePage.mustFillFields') ?></p>
                <button type="submit" class="btn btn--minimal">
                    <?= Yii::t('app', $model->isNewRecord ? 'ExcursionViewPage.addExcursion' : 'UpdateExcursionPage.saveButton') ?>
                </button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

