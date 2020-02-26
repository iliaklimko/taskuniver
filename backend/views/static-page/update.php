<?php

use yii\helpers\Html;
use yii\helpers\StringHelper;
use backend\components\widgets\HeaderMenu\HeaderMenu;

/* @var $this yii\web\View */
/* @var $model common\models\StaticPages */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'static page',
]) . StringHelper::truncate($model->title, 20);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Static pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => StringHelper::truncate($model->title, 20), 'url' => ''];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<?php echo HeaderMenu::widget([
        'brandLabel' => Yii::t('app', 'Edit') . ' ' . StringHelper::truncate($model->title, 20),
        'rightItems' => [
            [
                'label' => '<i class="fa fa-times-circle"></i>' . ' ' . Yii::t('app', 'Close'),
                'url' => ['index']
            ],
        ],
])  ?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
