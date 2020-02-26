<?php

use yii\helpers\Html;
use backend\components\widgets\HeaderMenu\HeaderMenu;

/* @var $this yii\web\View */
/* @var $model common\models\ExcursionType */

$this->title = Yii::t('app', 'Create Excursion type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Excursion type'), 'url' => ['index']];
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
