<?php

use himiklab\sitemap\behaviors\SitemapBehavior;
use yii\helpers\Url;
use common\models\Post;
use common\models\Excursion;
use common\models\StaticPage;

$audiences = ['deaf', 'blind', 'wheelchair-person', 'parents-with-babes'];
$postCategories = ['lifehack', 'news', 'experience', 'sight'];


$createStaticPageViewRule = function ($locale = null, $target_audience = null) {
    return [
        'class' => StaticPage::className(),
        'behaviors' => [
            'sitemap' => [
                'class' => SitemapBehavior::className(),
                'dataClosure' => function ($model) use ($locale, $target_audience) {
                    return [
                        'loc' => Yii::$app->urlManager->createAbsoluteUrl(['static-page/view', 'page_alias' => $model->url_alias, 'locale' => $locale, 'target_audience' => $target_audience]),
                        'lastmod' => null,
                    ];
                }
            ],
        ],
    ];
};

$createPostViewRule = function ($locale = null, $target_audience = null, $post_category = null) {
    return [
        'class' => Post::className(),
        'behaviors' => [
            'sitemap' => [
                'class' => SitemapBehavior::className(),
                'scope' => function ($model) {
                    $model
                        ->andWhere(['status' => 10])
                        ->andWhere(['language_code' => $locale ? 'en' : 'ru'])
                    ;
                },
                'dataClosure' => function ($model) use ($locale, $target_audience, $post_category) {
                    return [
                        'loc' => Yii::$app->urlManager->createAbsoluteUrl(['post/view', 'post_alias' => $model->url_alias, 'locale' => $locale, 'target_audience' => $target_audience, 'post_category' => $post_category]),
                        'lastmod' => null,
                        'news' => [
                            'publication' => [
                                'name' => $model->title,
                                'language' => is_null($locale) ? 'ru' : 'en',
                            ],
                            'publication_date' => $model->publication_date,
                            'title' => $model->title,
                        ],
                    ];
                }
            ],
        ],
    ];
};

$createExcursionViewRule = function ($locale = null, $target_audience = null) {
    $locale = is_null($locale) ? 'ru' : 'en';
    return [
        'class' => Excursion::className(),
        'behaviors' => [
            'sitemap' => [
                'class' => SitemapBehavior::className(),
                'scope' => function ($model) {
                    $model
                        ->andWhere(['status' => 10])
                        ->joinWith('translations')
                        ->andWhere(['{{%excursion_translation}}.language_code' => $locale])
                    ;
                },
                'dataClosure' => function ($model) use ($locale, $target_audience) {
                    return [
                        'loc' => Yii::$app->urlManager->createAbsoluteUrl([
                            'excursion/view', 
                            'excursion_id' => $model->id, 
                            'locale' => $locale, 
                            'target_audience' => $target_audience,
                        ]),
                        'lastmod' => null,
                    ];
                }
            ],
        ],
    ];
};

return [
    'class' => 'himiklab\sitemap\Sitemap',
    //'enableGzip' => true, 
    'cacheExpire' => 1,//YII_DEBUG ? 1 : 24 * 60 * 60, // 1 second. Default is 24 hours
    'urls' => [
        [
            'loc' => '/',
        ],
        [
            'loc' => '/en',
        ],
        [
            'loc' => 'excursions/en',
        ],
        [
            'loc' => 'excursions',
        ],
        [
            'loc' => 'posts/all',
        ],
        [
            'loc' => 'en/posts/all',
        ],
    ],
    'models' => [
        $createStaticPageViewRule(),
        $createStaticPageViewRule('en'),
        $createPostViewRule(),
        $createPostViewRule('en'),
        $createExcursionViewRule(),
        $createExcursionViewRule('en'),
    ],
];