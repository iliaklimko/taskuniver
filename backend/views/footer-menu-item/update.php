<?php

use yii\helpers\Html;
use backend\components\widgets\HeaderMenu\HeaderMenu;

/* @var $this yii\web\View */
/* @var $model common\models\City */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'menu item',
]) . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Footer menu'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ''];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<?php echo HeaderMenu::widget([
        'rightItems' => [
            ['label' => '<i class="fa fa-times-circle"></i>' . ' ' . Yii::t('app', 'Close'), 'url' => ['index']],
        ],
])  ?>

<div class="city-update box box-primary">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
