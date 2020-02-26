<?php

use frontend\components\widgets\Breadcrumbs\BreadcrumbsWidget as Breadcrumbs;
?>

<?= Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]) ?>
