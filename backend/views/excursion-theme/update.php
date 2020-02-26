<?php

use yii\helpers\Html;
use backend\components\widgets\HeaderMenu\HeaderMenu;

/* @var $this yii\web\View */
/* @var $model common\models\ExcursionTheme */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Excursion theme',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Excursion theme'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ''];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<?php echo HeaderMenu::widget([
        'rightItems' => [
            ['label' => '<i class="fa fa-times-circle"></i>' . ' ' . Yii::t('app', 'Close'), 'url' => ['index']],
        ],
])  ?>

<div class="language-update box box-primary">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
