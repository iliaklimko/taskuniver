<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\components\widgets\ActiveForm\ActiveForm;
use kartik\file\FileInput;
use kartik\select2\Select2;

// $this->registerJs(<<<'JS'
//     $("#user-interface_language").change(function () {
//         var $fullNameEnInput = $("#user-full_name_en");
//         if ($(this).val() == "ru") {
//             $fullNameEnInput.parent().show();
//         } else {
//            $fullNameEnInput.parent().hide();
//         }
//     });
// JS
// );

$pluginOptions = [];
if (!is_null($model->getUploadUrl('avatar'))) {
    $pluginOptions = [
        'initialPreview' => [
            Html::img(
                $model->getUploadUrl('avatar'), [
                    'class' => 'file-preview-picture img-responsive',
                    'style' => 'height: 160px;',
            ]),
        ],
        'initialCaption' => $model->avatar,
    ];
}

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(['options' => ['id' => 'user-form', 'enctype' => 'multipart/form-data']]); ?>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= Yii::t('app', 'User') ?></h3>
                    </div>
                    <div class="box-body">
                        <?php /*echo $form->field($model, 'user_group_id')->dropDownList(
                            ArrayHelper::map($userGroupList, 'id', 'name'),
                            ['prompt' => '<'.Yii::t('app', 'User Group').'>',]
                        );*/ ?>

                        <?= !$model->isNewRecord
                            ? $form->field($model, 'id')->textInput(['readonly' => true])
                            : null
                        ?>

                        <?php echo $form->field($model, 'interface_language')->label(Yii::t('app', 'Interface language'))->dropDownList(
                            array_combine(
                                Yii::$app->params['languagesAvailable'],
                                array_map(function ($lang) {return Yii::t('app', "Interface $lang");}, Yii::$app->params['languagesAvailable'])
                            )
                        ) ?>

                        <?= $form->field($model, 'email') ?>

                        <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'full_name_en')->textInput(['maxlength' => true]) ?>

                        <?= $model->isNewRecord
                            ? $form->field($model, 'plain_password')->textInput()
                            : null
                        ?>

                        <?= $form->field($model, 'phone')->textInput() ?>

                        <?= $form->field($model, 'bio')->textarea(['rows' => 6]) ?>

                        <?= $form->field($model, 'account_vkontakte') ?>

                        <?= $form->field($model, 'account_facebook') ?>

                        <?= $form->field($model, 'account_instagram') ?>

                        <?= $form->field($model, 'account_twitter') ?>

                        <?= $form->field($model, 'instant_confirmation')->checkbox() ?>

                        <?= $form->field($model, 'pay_percent')->textInput()->label('Price %'); ?>

                        <?= $form->field($model, 'can_paid_by_card')->checkbox()->label('Can be paid by card') ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= Yii::t('app', 'Avatar') ?></h3>
                    </div>
                    <div class="box-body">
                        <?= $form->field($model, 'avatar')->label(Yii::t('app', 'Image'))->widget(FileInput::className(), [
                            'pluginOptions' => $pluginOptions,
                        ])->hint('File size cannot exceed 5 Mb') ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-body">
                        <?= $form->field($model, 'languageIds')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(
                                $languageList,
                                'id',
                                function ($model) {return $model->translate('en')->name;}
                            ),
                            'options' => ['prompt' => '<'.Yii::t('app', 'Languages').'>', 'multiple' => true],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ]) ?>

                        <?= $form->field($model, 'cityIds')->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(
                                $cityList,
                                'id',
                                function ($model) {return $model->translate('en')->name;}
                            ),
                            'options' => ['prompt' => '<'.Yii::t('app', 'Cities').'>', 'multiple' => true],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group well">
    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
