<?php

use yii\helpers\Html;
use backend\components\widgets\HeaderMenu\HeaderMenu;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'User',
]) . $model->email;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<?php echo HeaderMenu::widget([
        'brandLabel' => Yii::t('app', 'Edit') . ' ' . $model->email,
        'leftItems' => [
            [
                'label' => '<i class="fa fa-list"></i> Excursions' . ' '. '(' . count($model->excursions) . ')',
                'url' => ['user/list-excursions', 'userId' => $model->id],
            ],
        ],
        'rightItems' => [
            [
                'label' => '<i class="fa fa-times-circle"></i>' . ' ' . Yii::t('app', 'Close'),
                'url' => ['index', 'groupAlias' => $model->group->alias]
            ],
        ],
])  ?>

<?= $this->render('_form', [
    'model' => $model,
    'userGroupList' => $userGroupList,
    'cityList' => $cityList,
    'languageList' => $languageList,
]) ?>
