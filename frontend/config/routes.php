<?php

return [
    [
        'pattern' => 'sitemap',
        'route' => 'sitemap/default/index',
        'suffix' => '.xml'
    ],

    '/count' => 'excursion/count-places-excursion',
    'materials.rss' => 'site/rss',
    /** ajax get */
    '<locale:(ru|en)>/search/load-autocomplete' => 'search/load-autocomplete',
    'search/load-autocomplete' => 'search/load-autocomplete',


    '<locale:(ru|en)>/site/confirm-retry' => 'site/confirm-retry',
    'site/confirm-retry' => 'site/confirm-retry',
    '<locale:(ru|en)>/signup' => 'site/signup',
    'signup' => 'site/signup',
    '<locale:(ru|en)>/signup-confirm' => 'site/signup-confirm',
    'signup-confirm' => 'site/signup-confirm',
    '<locale:(ru|en)>/reset-password' => 'site/reset-password',
    'reset-password' => 'site/reset-password',
    '<locale:(ru|en)>/site/<action:.+>' => 'site/<action>',
    'site/<action:.+>' => 'site/<action>',

    /** ajax post */
    'user/remove-avatar' => 'user/remove-avatar',
    'user/remove-excursion-featured-image' => 'user/remove-excursion-featured-image',
    'user/remove-excursion-image' => 'user/remove-excursion-image',
    '<locale:(ru|en)>/excursion/validate-filter' => 'excursion/validate-filter',
    'excursion/validate-filter' => 'excursion/validate-filter',
    /** ********* */

    '<locale:(ru|en)>/office/<action:(list-orders|list-excursions|edit-profile|create-excursion|update-excursion|delete-excursion)>' => 'user/<action>',
    'office/<action:(list-orders|list-excursions|edit-profile|create-excursion|update-excursion|delete-excursion)>' => 'user/<action>',

    'guides/view-profile' => 'user/view-profile',

    // groups
    '<locale:(ru|en)>/<target_audience:(deaf|blind|wheelchair-person|parents-with-babes)>/excursions/<group_code:\w[-\w]*\w+>/page-<page:\d+>' => 'excursion/index',
    '<target_audience:(deaf|blind|wheelchair-person|parents-with-babes)>/<group_code:\w[-\w]*\w+>/excursions/page-<page:\d+>' => 'excursion/index',
    '<locale:(ru|en)>/<target_audience:(deaf|blind|wheelchair-person|parents-with-babes)>/excursions/<group_code:\w[-\w]*\w+>' => 'excursion/index',
    '<target_audience:(deaf|blind|wheelchair-person|parents-with-babes)>/excursions/<group_code:\w[-\w]*\w+>' => 'excursion/index',
    '<locale:(ru|en)>/excursions/<group_code:\w[-\w]*\w+>/page-<page:\d+>' => 'excursion/index',
    'excursions/<group_code:\w[-\w]*\w+>/page-<page:\d+>' => 'excursion/index',
    '<locale:(ru|en)>/excursions/<group_code:\w[-\w]*\w+>' => 'excursion/index',
    'excursions/<group_code:\w[-\w]*\w+>' => 'excursion/index',

    '<locale:(ru|en)>/<target_audience:(deaf|blind|wheelchair-person|parents-with-babes)>/excursions/<group_code:\w[-\w]*\w+>/excursion-<excursion_id:\d+>' => 'excursion/view',
    '<target_audience:(deaf|blind|wheelchair-person|parents-with-babes)>/excursions/<group_code:\w[-\w]*\w+>/excursion-<excursion_id:\d+>' => 'excursion/view',
    '<locale:(ru|en)>/excursions/<group_code:\w[-\w]*\w+>/excursion-<excursion_id:\d+>' => 'excursion/view',
    'excursions/<group_code:\w[-\w]*\w+>/excursion-<excursion_id:\d+>' => 'excursion/view',


    '<locale:(ru|en)>/<target_audience:(deaf|blind|wheelchair-person|parents-with-babes)>/excursions/page-<page:\d+>' => 'excursion/index',
    '<target_audience:(deaf|blind|wheelchair-person|parents-with-babes)>/excursions/page-<page:\d+>' => 'excursion/index',
    '<locale:(ru|en)>/<target_audience:(deaf|blind|wheelchair-person|parents-with-babes)>/excursions' => 'excursion/index',
    '<target_audience:(deaf|blind|wheelchair-person|parents-with-babes)>/excursions' => 'excursion/index',
    '<locale:(ru|en)>/excursions/page-<page:\d+>' => 'excursion/index',
    'excursions/page-<page:\d+>' => 'excursion/index',
    '<locale:(ru|en)>/' => 'excursion/index',
    '/' => 'excursion/index',


    '<locale:(ru|en)>/<target_audience:(deaf|blind|wheelchair-person|parents-with-babes)>/posts/<post_category:(lifehack|news|experience|sight|all)>' => 'post/index',
    '<target_audience:(deaf|blind|wheelchair-person|parents-with-babes)>/posts/<post_category:(lifehack|news|experience|sight|all)>' => 'post/index',
    '<locale:(ru|en)>/posts/<post_category:(lifehack|news|experience|sight|all)>' => 'post/index',
    'posts/<post_category:(lifehack|news|experience|sight|all)>' => 'post/index',

    '<locale:(ru|en)>/<target_audience:(deaf|blind|wheelchair-person|parents-with-babes)>/posts/all/<post_alias:\w[-\w]*\w+>' => 'post/view',
    '<target_audience:(deaf|blind|wheelchair-person|parents-with-babes)>/posts/all/<post_alias:\w[-\w]*\w+>' => 'post/view',
    '<locale:(ru|en)>/posts/all/<post_alias:\w[-\w]*\w+>' => 'post/view',
    'posts/all/<post_alias:\w[-\w]*\w+>' => 'post/view',

    '<locale:(ru|en)>/<whatever:.+>' => 'error-page/error',
    '<whatever:.+>' => 'error-page/error',
];
