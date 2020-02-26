<?php

use yii\helpers\Html;
use backend\components\widgets\HeaderMenu\HeaderMenu;

/* @var $this yii\web\View */
/* @var $model common\models\Country */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Country',
]) . $model->translate('en')->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Countries'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->translate('en')->name, 'url' => ''];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<?php echo HeaderMenu::widget([
        'rightItems' => [
            ['label' => '<i class="fa fa-times-circle"></i>' . ' ' . Yii::t('app', 'Close'), 'url' => ['index']],
        ],
])  ?>

<div class="country-update box box-primary">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
