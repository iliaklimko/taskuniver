<?php

use yii\helpers\Html;
use yii\web\IdentityInterface;

$user = Yii::$app->user->identity;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">G</span><span class="logo-lg">Ilia</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <li><a href="/" target="_blank"><i class="fa fa-external-link"></i> Go to site (RUS)</a></li>
                <li><a href="/en" target="_blank"><i class="fa fa-external-link"></i> Go to site (ENG)</a></li>
                <!-- User Account: style can be found in dropdown.less -->
                <?php if ($user instanceof IdentityInterface): ?>
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= Yii::getAlias('@web') ?>/img/default_avatar.png" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?= $user->username ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= Yii::getAlias('@web') ?>/img/default_avatar.png" class="img-circle"
                                 alt="User Image"/>

                            <p>
                                <?= $user->username ?>
                                <small>Member since <?= date('M. Y', $user->created_at) ?></small>
                            </p>
                        </li>

                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <!-- <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                            </div> -->
                            <div class="pull-right">
                                <?= Html::a(
                                    'Sign out',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

            </ul>
        </div>
    </nav>
</header>
