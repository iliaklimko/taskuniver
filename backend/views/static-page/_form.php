<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use backend\components\widgets\ActiveForm\ActiveForm;
use yii\bootstrap\Tabs;
use sadovojav\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;

/* @var $this yii\web\View */
/* @var $model common\models\StaticPage */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">
                <?= $form->field($model, 'url_alias')
                    ->textInput()
                    ->hint('Any word character plus `-` sign')
                ?>
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
                                   . $form->field($model->translate($lang), "[$lang]body")->widget(CKEditor::className(), [
                                        'editorOptions' => ElFinder::ckeditorOptions(['elfinder', 'path' => 'static-pages/files'],[
                                            'toolbar' => [
                                                ['Source', 'NewPage'],
                                                ['PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],
                                                ['Replace', 'SelectAll', 'Scayt'],
                                                ['Format', 'Bold', 'Italic', 'Underline', 'StrikeThrough', '-', 'Outdent', 'Indent', 'RemoveFormat',
                                                    'Blockquote', 'HorizontalRule'],
                                                ['Table', 'NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight',
                                                    'JustifyBlock'],
                                                ['Image', 'oembed', 'Video', 'Iframe'],
                                                ['Link', 'Unlink'],
                                                ['Maximize', 'ShowBlocks'],
                                            ],
                                            'allowedContent' => true,
                                            'forcePasteAsPlainText' => true,
                                            'extraPlugins' => 'image2,widget,oembed',
                                            'language' => Yii::$app->language,
                                            'height' => 300,
                                        ])
                                     ])
                                   . $form->field($model->translate($lang), "[$lang]h1")->textInput()
                                   . $form->field($model->translate($lang), "[$lang]meta_description")->textarea(['rows' => 3])
                                   . $form->field($model->translate($lang), "[$lang]meta_keywords")->textInput()
                                   ,
                    ];
                }, Yii::$app->params['languagesAvailable']); ?>
                <?= Tabs::widget(['items' => $tabsItems]) ?>
            </div>
        </div>
    </div>
</div>
<div class="form-group well">
    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>

