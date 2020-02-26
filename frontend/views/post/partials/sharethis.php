<?php

use yii\web\View;

$lang = Yii::$app->language;
$this->registerJsFile('http://w.sharethis.com/button/buttons.js', ['position' => View::POS_HEAD]);
$this->registerJs(<<<JS
    stLight.options({
        publisher: "8b051ff3-b5bb-48db-8903-010cf422b300",
        doNotHash: false,
        doNotCopy: false,
        hashAddressBar: false,
        lang: "{$lang}"
    });
JS
, View::POS_HEAD);
?>

<span class='st_vkontakte_large' displayText='Vkontakte'></span>
<span class='st_facebook_large' displayText='Facebook'></span>
<span class='st_googleplus_large' displayText='Google +'></span>
<span class='st_twitter_large' displayText='Tweet'></span>
