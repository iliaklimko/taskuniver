<?php

use yii\helpers\Html;
use backend\components\widgets\HeaderMenu\HeaderMenu;

/* @var $this yii\web\View */
/* @var $model common\models\StaticPage */

$this->title = Yii::t('app', 'Create page');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Static pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo HeaderMenu::widget([
        'rightItems' => [
            ['label' => '<i class="fa fa-times-circle"></i>' . ' ' . Yii::t('app', 'Close'), 'url' => ['index']],
        ],
])  ?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
