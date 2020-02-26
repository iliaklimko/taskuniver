<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use backend\components\widgets\ActiveForm\ActiveForm;
use yii\widgets\MaskedInput;
use yii\bootstrap\Tabs;
use kartik\select2\Select2;
use backend\components\widgets\FileInput\FileInputWidget as FileInput;
use common\models\Excursion;
use common\models\ExcursionGroups;
use backend\assets\ModerationAsset;

ModerationAsset::register($this);

$pluginOptions = [];
if (!is_null($model->getUploadUrl('featured_image'))) {
    $pluginOptions = [
        'initialPreview' => [
            Html::img(
                $model->getUploadUrl('featured_image'), [
                    'class' => 'file-preview-picture img-responsive',
                    'style' => 'height: 160px;',
            ]),
        ],
        'initialCaption' => $model->featured_image,
    ];
}
$group = ExcursionGroups::find()->select('code')->where(['id' => $model->group_id])->one();

$urlParams = [
    'excursion/view',
    'excursion_id' => $model->id,
    'locale' => Yii::$app->request->get('langCode') == 'en' && $model->hasTranslation('en') ? 'en' : null,
    'mode' => 'force',
    'group_code' => !empty($group) ? $group->code : ''
];
$urlForce = !$model->isNewRecord
    ? Html::a(
        '<i class="fa fa-external-link"></i>',
        Yii::$app->urlManagerFrontend->createAbsoluteUrl($urlParams),
        ['target' => '_blank']
     )
    : null;

$arr = unserialize($model->date_array);

/* @var $this yii\web\View */
/* @var $model common\models\Excursion */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(['options' => ['id' => 'excursion-form', 'enctype' => 'multipart/form-data']]); ?>
<div class="row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?= Yii::t('app', 'Excursion') ?> <?= $urlForce ?></h3>
                            </div>
                            <div class="box-body">
                                <?= !$model->isNewRecord
                                    ? $form->field($model, 'id')->textInput(['readonly' => true])
                                    : null
                                ?>

                                <?= $form->field($model, 'user_id')->label(Yii::t('app', 'Guide'))->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(
                                        $userList,
                                        'id',
                                        function ($model) {
                                            $label = $model->email;
                                            if ($model->full_name) {
                                                $label .= " ({$model->full_name})";
                                            }
                                            return $label;
                                        }
                                    ),
                                    'options' => [
                                        'prompt' => '<'.Yii::t('app', 'Guide').'>',
                                        'disabled' => $model->isNewRecord ? false : true,
                                    ],
                                ]);  ?>

                                <?= $form->field($model, 'group_id')->label(Yii::t('app', 'Category'))->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(
                                        $excursionGroupsList,
                                        'id',
                                        function ($model) {
                                            $label = $model->name;
                                            return $label;
                                        }
                                    ),
                                    'options' => [
                                        'prompt' => '<'.Yii::t('app', 'Group').'>',
                                        'disabled' => false,//$model->isNewRecord ? false : true,
                                    ],
                                ]);  ?>

                                <?= $form->field($model, 'targetAudienceIds')->label(Yii::t('app', 'Target audiences'))->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(
                                        $targetAudienceList,
                                        'id',
                                        function ($model) {return $model->name;}
                                    ),
                                    'options' => ['prompt' => '<'.Yii::t('app', 'Target audiences').'>', 'multiple' => true],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-body">
                                <?php $tabsItems = array_map(function ($lang) use ($model, $form){
                                    return [
                                        'label' => Yii::t('app', $lang),
                                        'content' => $form->field($model->translate($lang), "[$lang]title")->textInput()
                                                   . $form->field($model->translate($lang), "[$lang]description")->textarea(['rows' => 4])
                                                   . $form->field($model->translate($lang), "[$lang]meeting_point")->textarea(['rows' => 4])
                                                   . $form->field($model->translate($lang), "[$lang]additional_info")->textarea(['rows' => 4])
                                                   . $form->field($model->translate($lang), "[$lang]included_in_price")->textInput()->hint('Separate with `,`')
                                                   . $form->field($model->translate($lang), "[$lang]not_included_in_price")->textInput()->hint('Separate with `,`')
                                                   . $form->field($model, 'tagValues')->label(Yii::t('app', 'Tags'))->widget(Select2::classname(), [
                                                        'options' => ['prompt' => '<'.Yii::t('app', 'Tags').'>', 'multiple' => true],
                                                        'pluginOptions' => [
                                                            'tags' => true,
                                                            'maintainOrder' => true,
                                                            'tokenSeparators' => [','],
                                                            'allowClear' => true,
                                                            'ajax' => [
                                                                'url' => Url::to([$lang . '/post/load-tag-list']),
                                                            ],
                                                        ],
                                                     ])
                                                   ,
                                    ];
                                }, [Yii::$app->request->get('langCode')]); ?>
                                <?= Tabs::widget(['items' => $tabsItems]) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-body">
                                <?php if ($model->isNewRecord): ?>
                                <?= $form->field($uploadForm, 'files[]')->widget(FileInput::classname(), [
                                    'options' => [
                                        'multiple' => true,
                                        'accept' => 'image/*',
                                    ]
                                ])->label('Additional images')->hint('File size cannot exceed 5 Mb') ?>
                                <?php endif; ?>
                                <?php /*echo $form->field($model, 'start_time')->hint(Yii::t('app', 'Format HH:ii'))->widget(MaskedInput::className(), [
                                    'mask' => '99:99',
                                ]);*/ ?>
                                <?= $form->field($model, 'person_number')->dropDownList(Excursion::getPersonNumberList(), [
                                    'prompt' => Yii::t('app', '<'.Yii::t('app', 'Person Number').'>')
                                ]) ?>
                                <?= $form->field($model, 'duration')->dropDownList(Excursion::getDurationList(), [
                                    'prompt' => Yii::t('app', '<'.Yii::t('app', 'Duration').'>')
                                ]) ?>
                                <?= $form->field($model, 'currency_id')->label(Yii::t('app', 'Currency'))->dropDownList(
                                    ArrayHelper::map($currencyList, 'id', function ($currency) {
                                        return $currency->name . ' ' . '(' . $currency->code . ')';
                                    }),
                                    ['prompt' => '<' . Yii::t('app', 'Currency') . '>']
                                ) ?>
                                <?= $form->field($model, 'current_price')->textInput(['onkeydown' => 'javascript: return ((event.keyCode>47)&&(event.keyCode<58)||(event.keyCode==8)||((event.keyCode>95)&&(event.keyCode<106)))']) ?>
                                <?= $form->field($model, 'old_price')->textInput(['onkeydown' => 'javascript: return ((event.keyCode>47)&&(event.keyCode<58)||(event.keyCode==8)||((event.keyCode>95)&&(event.keyCode<106)))']) ?>
                                <?= $form->field($model, 'set_to')->textInput() ?>
                                <?= $form->field($model, 'free_cancellation')->textInput() ?>

                                <div class="form-group field-excursion-mondayDay">
                                    <div class="checkbox">
                                        <input id="mondayValue" type="hidden" value="N" name="Excursion[mondayDays]">
                                        <label for="excursion-mondayDay">
                                            <input type="checkbox"
                                                   id="monday"
                                                   name="Excursion[mondayDays]"
                                                   value="<?= ($arr['monday']['active'] == 'Y') ? 'Y' : 'N'  ?>"
                                                   class="days"
                                                <?= ($arr['monday']['active'] == 'Y') ? 'checked' : null  ?>

                                            >
                                            Monday
                                        </label>
                                        <p class="help-block help-block-error"></p>

                                    </div>
                                </div>

                                <div class="form-group field-excursion-pick_up_from_hotel">
                                    <div class="checkbox">
                                        <label for="excursion-pick_up_from_hotel">
                                            <input id="mondayValue" type="hidden" value="N" name="Excursion[tuesdayDays]">
                                            <input type="hidden" name="Excursion[tuesday]" value="Y">
                                            <input type="checkbox"
                                                   id="tuesday"
                                                   name="Excursion[tuesdayDays]"
                                                   value="<?= ($arr['tuesday']['active'] == 'Y') ? 'Y' : 'N'  ?>"
                                                   class="days"
                                                <?= ($arr['tuesday']['active'] == 'Y') ? 'checked' : null  ?>

                                            >
                                            Tuesday
                                        </label>
                                        <p class="help-block help-block-error"></p>

                                    </div>
                                </div>

                                <div class="form-group field-excursion-pick_up_from_hotel">
                                    <div class="checkbox">
                                        <label for="excursion-pick_up_from_hotel">
                                            <input id="mondayValue" type="hidden" value="N" name="Excursion[wednesdayDays]">
                                            <input type="checkbox"
                                                   id="wednesday"
                                                   name="Excursion[wednesdayDays]"
                                                   value="<?= ($arr['wednesday']['active'] == 'Y') ? 'Y' : 'N'  ?>"
                                                   class="days"
                                                <?= ($arr['wednesday']['active'] == 'Y') ? 'checked' : null  ?>

                                            >
                                            Wednesday
                                        </label>
                                        <p class="help-block help-block-error"></p>

                                    </div>
                                </div>

                                <div class="form-group field-excursion-pick_up_from_hotel">
                                    <div class="checkbox">
                                        <label for="excursion-pick_up_from_hotel">
                                            <input id="mondayValue" type="hidden" value="N" name="Excursion[thursdayDays]">
                                            <input type="checkbox"
                                                   id="thursday"
                                                   name="Excursion[thursdayDays]"
                                                   value="<?= ($arr['thursday']['active'] == 'Y') ? 'Y' : 'N'  ?>"
                                                   class="days"
                                                <?= ($arr['thursday']['active'] == 'Y') ? 'checked' : null  ?>

                                            >
                                            Thursday
                                        </label>
                                        <p class="help-block help-block-error"></p>

                                    </div>
                                </div>

                                <div class="form-group field-excursion-pick_up_from_hotel">
                                    <div class="checkbox">
                                        <label for="excursion-pick_up_from_hotel">
                                            <input id="mondayValue" type="hidden" value="N" name="Excursion[fridayDays]">
                                            <input type="checkbox"
                                                   id="friday"
                                                   name="Excursion[fridayDays]"
                                                   value="<?= ($arr['friday']['active'] == 'Y') ? 'Y' : 'N'  ?>"
                                                   class="days"
                                                <?= ($arr['friday']['active'] == 'Y') ? 'checked' : null  ?>

                                            >
                                            Friday
                                        </label>
                                        <p class="help-block help-block-error"></p>

                                    </div>
                                </div>

                                <div class="form-group field-excursion-pick_up_from_hotel">
                                    <div class="checkbox">
                                        <label for="excursion-pick_up_from_hotel">
                                            <input id="mondayValue" type="hidden" value="N" name="Excursion[saturdayDays]">
                                            <input type="checkbox"
                                                   id="saturday"
                                                   name="Excursion[saturdayDays]"
                                                   value="<?= ($arr['saturday']['active'] == 'Y') ? 'Y' : 'N'  ?>"
                                                   class="days"
                                                <?= ($arr['saturday']['active'] == 'Y') ? 'checked' : null  ?>

                                            >
                                            Saturday
                                        </label>
                                        <p class="help-block help-block-error"></p>

                                    </div>
                                </div>

                                <div class="form-group field-excursion-pick_up_from_hotel">
                                    <div class="checkbox">
                                        <label for="excursion-pick_up_from_hotel">
                                            <input id="mondayValue" type="hidden" value="N" name="Excursion[sundayDays]">
                                            <input type="checkbox"
                                                   id="sunday"
                                                   name="Excursion[sundayDays]"
                                                   value="<?= ($arr['sunday']['active'] == 'Y') ? 'Y' : 'N'  ?>"
                                                   class="days"
                                                <?= ($arr['sunday']['active'] == 'Y') ? 'checked' : null  ?>

                                            >
                                            Sunday
                                        </label>
                                        <p class="help-block help-block-error"></p>

                                    </div>
                                </div>

                                <div class="form-group field-excursion-monday">
                                    <label class="control-label" for="excursion-free_cancellation">Monday count</label>
                                    <input id="mondayValue" type="hidden" value="N" name="Excursion[mondayDay]">
                                    <input type="text" class="form-control" value="<?= (!empty($arr['monday']['count'])) ? $arr['monday']['count'] : 0 ?>"  id="excursion-free_cancellation" name="Excursion[monday]" onkeydown="javascript: return ((event.keyCode>47)&amp;&amp;(event.keyCode<58)||(event.keyCode==8)||((event.keyCode>95)&amp;&amp;(event.keyCode<106)))">
                                    <p class="help-block help-block-error"></p>
                                </div>
                                <div class="form-group field-excursion-free_cancellation">
                                    <label class="control-label" for="excursion-tuesday">Tuesday count</label>
                                    <input id="tuesdayValue" type="hidden" value="N" name="Excursion[tuesdayDay]">
                                    <input type="text" class="form-control" value="<?= (!empty($arr['tuesday']['count'])) ? $arr['tuesday']['count'] : 0 ?>"  id="excursion-free_cancellation" name="Excursion[tuesday]" onkeydown="javascript: return ((event.keyCode>47)&amp;&amp;(event.keyCode<58)||(event.keyCode==8)||((event.keyCode>95)&amp;&amp;(event.keyCode<106)))">

                                    <p class="help-block help-block-error"></p>
                                </div>
                                <div class="form-group field-excursion-free_cancellation">
                                    <label class="control-label" for="excursion-free_cancellation">Wednesday count</label>
                                    <input id="wednesdayValue" type="hidden" value="N" name="Excursion[wednesdayDay]">
                                    <input type="text" class="form-control" value="<?= (!empty($arr['wednesday']['count'])) ? $arr['wednesday']['count'] : 0 ?>"  id="excursion-free_cancellation" name="Excursion[wednesday]" onkeydown="javascript: return ((event.keyCode>47)&amp;&amp;(event.keyCode<58)||(event.keyCode==8)||((event.keyCode>95)&amp;&amp;(event.keyCode<106)))">

                                    <p class="help-block help-block-error"></p>
                                </div>
                                <div class="form-group field-excursion-free_cancellation">
                                    <label class="control-label" for="excursion-free_cancellation">Thursday count</label>
                                    <input id="thursdayValue" type="hidden" value="N" name="Excursion[thursdayDay]">
                                    <input type="text" class="form-control" value="<?= (!empty($arr['thursday']['count'])) ? $arr['thursday']['count'] : 0 ?>"  id="excursion-free_cancellation" name="Excursion[thursday]" onkeydown="javascript: return ((event.keyCode>47)&amp;&amp;(event.keyCode<58)||(event.keyCode==8)||((event.keyCode>95)&amp;&amp;(event.keyCode<106)))">

                                    <p class="help-block help-block-error"></p>
                                </div>
                                <div class="form-group field-excursion-free_cancellation">
                                    <label class="control-label" for="excursion-free_cancellation">Friday count</label>
                                    <input id="fridayValue" type="hidden" value="N" name="Excursion[fridayDay]">
                                    <input type="text" class="form-control" value="<?= (!empty($arr['friday']['count'])) ? $arr['friday']['count'] : 0 ?>"  id="excursion-free_cancellation" name="Excursion[friday]" onkeydown="javascript: return ((event.keyCode>47)&amp;&amp;(event.keyCode<58)||(event.keyCode==8)||((event.keyCode>95)&amp;&amp;(event.keyCode<106)))">

                                    <p class="help-block help-block-error"></p>
                                </div>
                                <div class="form-group field-excursion-free_cancellation">
                                    <label class="control-label" for="excursion-free_cancellation">Saturday count</label>
                                    <input id="saturdayValue" type="hidden" value="N" name="Excursion[saturdayDay]">
                                    <input type="text" class="form-control" value="<?= (!empty($arr['saturday']['count'])) ? $arr['saturday']['count'] : 0 ?>"  id="excursion-free_cancellation" name="Excursion[saturday]" onkeydown="javascript: return ((event.keyCode>47)&amp;&amp;(event.keyCode<58)||(event.keyCode==8)||((event.keyCode>95)&amp;&amp;(event.keyCode<106)))">

                                    <p class="help-block help-block-error"></p>
                                </div>
                                <div class="form-group field-excursion-free_cancellation">
                                    <label class="control-label" for="excursion-free_cancellation">Sunday count</label>
                                    <input id="sundayValue" type="hidden" value="N" name="Excursion[sundayDay]">
                                    <input type="text" class="form-control" value="<?= (!empty($arr['sunday']['count'])) ? $arr['sunday']['count'] : 0 ?>"  id="excursion-free_cancellation" name="Excursion[sunday]" onkeydown="javascript: return ((event.keyCode>47)&amp;&amp;(event.keyCode<58)||(event.keyCode==8)||((event.keyCode>95)&amp;&amp;(event.keyCode<106)))">

                                    <p class="help-block help-block-error"></p>
                                </div>

                                <?= $form->field($model, 'time_spending')->textInput() ?>
                                <?= $form->field($model, 'pick_up_from_hotel')->checkbox() ?>
                                <?= $form->field($model, 'similarExcursionIds')->label(Yii::t('app', 'Similar excursions'))->widget(Select2::classname(), [
                                    'data' => ArrayHelper::map(
                                        $excursionList,
                                        'id',
                                        function ($model) {
                                            $title = $model->hasTranslation('en')
                                                ? $model->translate('en')->title
                                                : $model->translate('ru')->title;
                                            return StringHelper::truncate($title, 30);
                                        }
                                    ),
                                    'options' => ['prompt' => '<'.Yii::t('app', 'Similar excursions').'>', 'multiple' => true],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= Yii::t('app', 'Featured image') ?></h3>
                    </div>
                    <div class="box-body">
                        <?= $form->field($model, 'featured_image')->widget(FileInput::className(), [
                            'pluginOptions' => $pluginOptions,
                            'options' => [
                                'data-model-class' => $model->className(),
                                'data-model-id' => $model->id,
                                'data-model-attribute' => 'featured_image',
                            ],
                        ])->hint('File size cannot exceed 5 Mb. Width >= 1500 px') ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= Yii::t('app', 'Additional information') ?></h3>
                    </div>
                    <div class="box-body">
                        <?= $form->field($model, 'languageIds')->label(Yii::t('app', 'Excursion Languages'))->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(
                                $languageList,
                                'id',
                                function ($model) {return $model->name;}
                            ),
                            'options' => ['prompt' => '<'.Yii::t('app', 'Excursion Languages').'>', 'multiple' => true],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ]) ?>

                        <?= $form->field($model, 'typeIds')->label(Yii::t('app', 'Excursion types'))->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(
                                $excursionTypeList,
                                'id',
                                function ($model) {return $model->name;}
                            ),
                            'options' => ['prompt' => '<'.Yii::t('app', 'Excursion types').'>', 'multiple' => true],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ]) ?>

                        <?= $form->field($model, 'themeIds')->label(Yii::t('app', 'Excursion themes'))->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(
                                $excursionThemeList,
                                'id',
                                function ($model) {return $model->name;}
                            ),
                            'options' => ['prompt' => '<'.Yii::t('app', 'Excursion themes').'>', 'multiple' => true],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ]) ?>

                        <?= $form->field($model, 'start_city_id')->label(Yii::t('app', 'Start City'))->widget(Select2::classname(), [
                            'data' => ArrayHelper::map($cityList, 'id', function ($city) {
                                return $city->name;
                            }),
                            'options' => [
                                'prompt' => '<'.Yii::t('app', 'Guide').'>',
                            ],
                        ]);  ?>

                        <?= $form->field($model, 'cityIds')->label(Yii::t('app', 'Cities on the way'))->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(
                                $cityList,
                                'id',
                                function ($model) {return $model->name;}
                            ),
                            'options' => ['prompt' => '<'.Yii::t('app', 'Cities').'>', 'multiple' => true],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ]) ?>
                        <?= $form->field($model, 'new_cities')->label('New City')->hint('Format ru (en)') ?>

                        <?= $form->field($model, 'sightIds')->label(Yii::t('app', 'Sights on the way'))->widget(Select2::classname(), [
                            'data' => ArrayHelper::map(
                                $sightList,
                                'id',
                                function ($model) {return $model->name;}
                            ),
                            'options' => ['prompt' => '<'.Yii::t('app', 'Sights').'>', 'multiple' => true],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ]) ?>
                        <?= $form->field($model, 'new_sights')->label('New Sight')->hint('Format ru (en)') ?>
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
