<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        "css/dist/js/vendors/slick/slick.css",
        "css/dist/js/vendors/fancybox/jquery.fancybox.min.css",
        "css/dist/js/vendors/formstyler/jquery.formstyler.css",
        "css/dist/js/vendors/ui/jqueryui.min.css",
        "css/dist/js/vendors/multipleSelect/multiple-select.css",
        "css/dist/css/style.css",
        "https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css",
        'css/custom.css',
    ];
    public $js = [
        "css/dist/js/vendors/slick/slick.min.js",
        "css/dist/js/vendors/fancybox/jquery.fancybox.pack.js",
        "css/dist/js/vendors/formstyler/jquery.formstyler.min.js",
        "css/dist/js/vendors/jtruncate/jTruncate.min.js",
        "css/dist/js/vendors/multipleSelect/multiple-select.js",
        "https://code.jquery.com/ui/1.11.1/jquery-ui.js",
        'js/vendors/jquery-ui/datepicker/datepicker-ru.js',
        "dist/js/vendors/inputmask/jquery.inputmask.bundle.min.js",
        "js/interface.js",
        'js/custom.js',
        "js/favorites.js",
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
