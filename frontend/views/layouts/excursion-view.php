<?php $this->beginContent('@frontend/views/layouts/base.php'); ?>

<!--LAYOUT-->
<div class="layout layout--full">
    <?= $this->render('partials/view-page-header') ?>
    <div class="wrapper">
        <?= $content ?>
    </div>
    <?= $this->render('partials/footer') ?>
</div><!-- END LAYOUT-->

<?php $this->endContent(); ?>