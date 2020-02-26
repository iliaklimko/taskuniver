<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\web\View;
use frontend\assets\SearchAsset;
use frontend\assets\AppAsset;
use frontend\components\widgets\SocialSeo\SocialSeoWidget as SocialSeo;

$this->registerJsFile(
    '@web/js/translator.js',
    ['position' => View::POS_HEAD]
);
$this->registerJs(
    'window.translator = new Translator('
    . json_encode(require __DIR__.'/../../messages/'.Yii::$app->language.'/app.php')
    . ');',
    View::POS_HEAD);
$this->registerJs(
    'var searchUrl = "'
  . Url::to([
        '/search/load-autocomplete',
        'target_audience' => Yii::$app->request->get('target_audience'),
        'locale' => Yii::$app->language !== 'ru' ? 'en' : null,
    ])
  . '";'
  , View::POS_HEAD);

AppAsset::register($this);

if (!(in_array(Yii::$app->controller->id, ['post', 'static-page'])
  && Yii::$app->controller->action->id == 'view')) {
    $this->params['specialSeo']['title'] = Yii::t('app', 'MainPage.indexTitle');
    $this->params['specialSeo']['description'] = Yii::t('app', 'MainPage.indexSubTitle');
    $this->params['specialSeo']['image'] = null;
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1200">
    <?= Html::csrfMetaTags() ?>
    <?= SocialSeo::widget(['meta' => isset($this->params['specialSeo']) ? $this->params['specialSeo'] : null]) ?>
    <?= !empty($this->params['analytics']) ? $this->params['analytics']->body : null ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16" />
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?= $content ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
