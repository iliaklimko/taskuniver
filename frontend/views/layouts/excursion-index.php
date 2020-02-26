<?php $this->beginContent('@frontend/views/layouts/base.php'); ?>

<!--LAYOUT-->
<div class="layout">
    <?= $this->render('partials/header') ?>
    <div class="wrapper">
        <?= $content ?>
    </div>
    <?= $this->render('partials/footer') ?>
</div><!-- END LAYOUT-->

<?php $this->endContent(); ?>
