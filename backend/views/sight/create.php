<?php

use yii\helpers\Html;
use backend\components\widgets\HeaderMenu\HeaderMenu;

/* @var $this yii\web\View */
/* @var $model common\models\Sight */

$this->title = Yii::t('app', 'Create Sight');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Sights'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Create');
?>

<?php echo HeaderMenu::widget([
        'rightItems' => [
            ['label' => '<i class="fa fa-times-circle"></i>' . ' ' . Yii::t('app', 'Close'), 'url' => ['index']],
        ],
])  ?>

<div class="currency-create box box-primary">

    <?= $this->render('_form', [
        'model' => $model,
        'cityList' => $cityList,
    ]) ?>

</div>