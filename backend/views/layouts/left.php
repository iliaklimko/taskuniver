<?php

use common\models\UserGroup;
use common\models\FooterMenuItem;
?>

<aside class="main-sidebar">

    <section class="sidebar">

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Menu', 'options' => ['class' => 'header']],


                    ['label' => Yii::t('app', 'Languages'), 'icon' => 'fa fa-language', 'url' => ['/language/index']],
                    ['label' => Yii::t('app', 'Currencies'), 'icon' => 'fa fa-money', 'url' => ['/currency/index']],
                    ['label' => Yii::t('app', 'Target audiences'), 'icon' => 'fa fa-wheelchair', 'url' => ['/target-audience/index']],
                    [
                        'label' => Yii::t('app', 'Places'),
                        'icon' => 'fa fa-globe',
                        'url' => '#',
                        'items' => [
                            ['label' => Yii::t('app', 'Countries'), 'icon' => 'fa fa-circle-o', 'url' => ['/country/index']],
                            ['label' => Yii::t('app', 'Cities'), 'icon' => 'fa fa-circle-o', 'url' => ['/city/index']],
                            ['label' => Yii::t('app', 'Sights'), 'icon' => 'fa fa-map-marker', 'url' => ['/sight/index']],
                        ]
                    ],
                    [
                        'label' => Yii::t('app', 'Tags'),
                        'icon' => 'fa fa-tags',
                        'url' => ['/tag'],
                        'items' => [
                            ['label' => Yii::t('app', 'In English'), 'icon' => 'fa fa-circle-o', 'url' => ['/tag/index', 'langCode' => 'en']],
                            ['label' => Yii::t('app', 'In Russian'), 'icon' => 'fa fa-circle-o', 'url' => ['/tag/index', 'langCode' => 'ru']],
                        ],
                    ],
                    [
                        'label' => Yii::t('app', 'Excursions'),
                        'icon' => 'fa fa-th',
                        'url' => ['/excursion/index'],
                        'items' => [
                            ['label' => Yii::t('app', 'Excursion types'), 'icon' => 'fa fa-circle-o', 'url' => ['/excursion-type/index']],
                            ['label' => Yii::t('app', 'Excursion themes'), 'icon' => 'fa fa-circle-o', 'url' => ['/excursion-theme/index']],
                            ['label' => Yii::t('app', 'Excursion List'), 'icon' => 'fa fa-circle-o', 'url' => ['/excursion/index']],
                            ['label' => Yii::t('app', 'Excursion categories'), 'icon' => 'fa fa-circle-o', 'url' => ['/excursion-groups/index']],
                        ],
                    ],
                    [
                        'label' => Yii::t('app', 'Guides'),
                        'icon' => 'fa fa-users',
                        'url' => ['/user/index', 'groupAlias' => UserGroup::ALIAS_GUIDE],
                    ],


                ],
            ]
        ) ?>

    </section>

</aside>
