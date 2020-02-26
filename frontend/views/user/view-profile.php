<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = $model->full_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'user_group_id',
                'value' => $model->group->name,
            ],
            'email:email',
            [
                'attribute' => 'avatar',
                'format' => 'html',
                'value' => Html::img($model->getUploadUrl('avatar'), [
                    'style' => 'height: 50px;'
                ])
            ],
            'phone',
            'full_name',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d H:i'],
            ],
            'bio:ntext',
        ],
    ]) ?>

</div>
