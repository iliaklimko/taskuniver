<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use frontend\components\helpers\BaseHelper;
use common\models\FooterMenuItem;

$routes = [
    'excursion/index',
    'excursion/view',
    'post/index',
    'post/view',
];
$returnTo = in_array(Yii::$app->controller->route, $routes)
    ? Yii::$app->getRequest()->getUrl()
    : null;

$footerMenu = ArrayHelper::index(
    $this->params['footerMenu'],
    null,
    'column'
);
if (!isset($footerMenu[FooterMenuItem::COLUMN_01])) {
    $footerMenu[FooterMenuItem::COLUMN_01] = [];
}
if (!isset($footerMenu[FooterMenuItem::COLUMN_02])) {
    $footerMenu[FooterMenuItem::COLUMN_02] = [];
}
if (!isset($footerMenu[FooterMenuItem::COLUMN_03])) {
    $footerMenu[FooterMenuItem::COLUMN_03] = [];
}
?>

<footer class="page-footer">
    <div id="test">
    <a class="guide-enter fb-inline"
       href="#guide-enter"
       style="<?= !Yii::$app->user->isGuest ? 'display:none;' : null ?>"
    >
        <i></i>
        <span><?= Yii::t('app', 'Footer.guideEnter') ?></span>
    </a>
    <?php if (!Yii::$app->user->isGuest): ?>
        <a class="guide-enter fb-inline"
           href="<?= Url::to([
               '/user/edit-profile',
               'locale' => Yii::$app->user->identity->interface_language != 'ru' ? Yii::$app->user->identity->interface_language : null,
               'target_audience' => Yii::$app->request->get('target_audience')
           ]) ?>"
        >
            <span><?= Yii::t('app', 'Footer.guideProfile') ?></span>
        </a>
        <?= Html::a(Yii::t('app', 'Footer.guideLogout'), ['/site/logout', 'returnTo' => $returnTo], [
            'id' => 'site-logout',
            'class' => 'guide-enter fb-inline',
            'data-method' => 'post',
            'data-pjax' => '0',
        ]) ?>
    <?php endif; ?>
    </div>
</footer>

<?= $this->render('signup-popup') ?>
<?= $this->render('request-password-reset-popup') ?>
