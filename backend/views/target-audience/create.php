<?php

use yii\helpers\Html;
use backend\components\widgets\HeaderMenu\HeaderMenu;

/* @var $this yii\web\View */
/* @var $model common\models\TargetAudience */

$this->title = Yii::t('app', 'Create Target audience');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Target audience'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<?php echo HeaderMenu::widget([
        'rightItems' => [
            ['label' => '<i class="fa fa-times-circle"></i>' . ' ' . Yii::t('app', 'Close'), 'url' => ['index']],
        ],
])  ?>

<div class="language-create box box-primary">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
