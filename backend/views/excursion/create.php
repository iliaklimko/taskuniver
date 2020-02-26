<?php

use yii\helpers\Html;
use backend\components\widgets\HeaderMenu\HeaderMenu;

/* @var $this yii\web\View */
/* @var $model common\models\ExcursionType */


$toggleLang = Yii::$app->request->get('langCode', 'en') == 'en'
    ? ['lang' => 'ru', 'label' => Yii::t('app', 'To russian version')]
    : ['lang' => 'en', 'label' => Yii::t('app', 'To english version')]
;

$this->title = Yii::t('app', 'Create Excursion');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Excursion list'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<?php echo HeaderMenu::widget([
        'leftItems' => [
            ['label' => $toggleLang['label'], 'url' => ['create', 'langCode' => $toggleLang['lang']]],
        ],
        'rightItems' => [
            ['label' => '<i class="fa fa-times-circle"></i>' . ' ' . Yii::t('app', 'Close'), 'url' => ['index']],
        ],
])  ?>

<?= $this->render('_form', [
    'model' => $model,
    'uploadForm' => $uploadForm,
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
