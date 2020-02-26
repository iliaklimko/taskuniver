<?php

use yii\helpers\Html;
use yii\helpers\Url;

$targetLanguage = Yii::$app->language == 'ru' ? 'en' : null;
$targetLanguageAbbr = $targetLanguage ? 'рус' : 'eng';
$currentLanguageAbbr = $targetLanguage ? 'eng' : 'рус';
$currentLanguageImg = $targetLanguage ? 'rus' : 'eng';
?>

<div class="page-header__language">
    <form id="language-changer-form" action="<?= Url::current(['locale' => $targetLanguage]) ?>" method="get">
        <span class="lang-img">
            <img src="/frontend/web/css/dist/img/content/<?= $currentLanguageImg ?>.png">
        </span>
        <select class="fs" name="locale">
            <option value="notnull"><?= $targetLanguageAbbr ?></option>
            <option value="notnull"><?= $currentLanguageAbbr ?></option>
        </select>
    </form>
</div>
