<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use backend\components\widgets\HeaderMenu\HeaderMenu;

/* @var $this yii\web\View */
/* @var $model common\models\Excursion */

$toggleLang = Yii::$app->request->get('langCode', 'en') == 'en'
    ? ['lang' => 'ru', 'label' => Yii::t('app', 'To russian version')]
    : ['lang' => 'en', 'label' => Yii::t('app', 'To english version')]
;

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Excursion',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Excursion list'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ''];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<?php echo HeaderMenu::widget([
        // 'brandLabel' => Yii::t('app', 'Edit') . ' ' . StringHelper::truncate($model->title, 20),
        'leftItems' => [
                ['label' => $toggleLang['label'], 'url' => ['update', 'id' => $model->id, 'langCode' => $toggleLang['lang']]],
                [
                    'label' => '<i class="fa fa-camera"></i>' . ' ' . Yii::t('app', 'Gallery') . ' ' . '(' . count($model->images) . ')',
                    'url' => ['excursion-image/index', 'excursionId' => $model->id],
                ],
            ],
        'rightItems' => [
            ['label' => '<i class="fa fa-times-circle"></i>' . ' ' . Yii::t('app', 'Close'), 'url' => ['index']],
        ],
])  ?>

<?= $this->render('_form', [
    'model' => $model,
    'userList' => $userList,
    'targetAudienceList' => $targetAudienceList,
    'cityList' => $cityList,
    'excursionTypeList' => $excursionTypeList,
    'excursionThemeList' => $excursionThemeList,
    'sightList' => $sightList,
    'languageList' => $languageList,
    'excursionList' => $excursionList,
    'currencyList' => $currencyList,
    'excursionGroupsList' => $excursionGroupsList
]) ?>
