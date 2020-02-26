<?php

use yii\helpers\Url;
?>

<?php if (!empty($meta)): ?>
    <?php if (!empty($meta['title'])): ?>
    <meta property="og:title" content="<?= $meta['title'] ?>">
    <meta name="twitter:title" content="<?= $meta['title'] ?>">
    <?php endif; ?>
    <?php if (!empty($meta['description'])): ?>
    <meta property="og:description" content="<?= $meta['description'] ?>">
    <meta name="twitter:description" content="<?= $meta['description'] ?>">
    <?php endif; ?>
    <meta property="og:image" content="<?= !empty($meta['image'])
                                         ? Url::base(true) . $meta['image']
                                         : Url::to('@web/images/fb_sharing.png', true)
                                       ?>">
    <meta name="twitter:image:src" content="<?= !empty($meta['image'])
                                         ? Url::base(true) . $meta['image']
                                         : Url::to('@web/images/fb_sharing.png', true)
                                       ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta property="og:type" content="website">
    <!-- <meta name="twitter:domain" content="http://sport.mts.by"> -->
<?php endif; ?>
