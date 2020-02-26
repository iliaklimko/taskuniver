<?php

namespace frontend\components\widgets\OfficeMenu;

use Yii;
use yii\widgets\Menu;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use frontend\components\helpers\BaseHelper;

class OfficeMenuWidget extends Menu
{
    public $showListExcursions = true;
    public $showListOrders = true;
    public $newOrdersCount = 0;
    public $encodeLabels = false;
    public $options = [
        'tag'   => 'div',
        'class' => 'cabinet__links',
    ];
    public $itemOptions    = ['tag' => null];
    public $linkTemplate   = '<a href="{url}" class="{class}">{label}</a>';
    public $activeCssClass = 'cabinet__links-item--active';

    public function init()
    {
        $items = [
            [
                'label' => Yii::t('app', 'OfficeMenuWidget.personalTab'),
                'url'   => ['/user/edit-profile', 'locale' => Yii::$app->request->get('locale')],
            ],
            [
                'label'   => Yii::t('app', 'OfficeMenuWidget.excursionsTab'),
                'url'     => ['/user/list-excursions', 'locale' => Yii::$app->request->get('locale')],
                'visible' => $this->showListExcursions,
            ],
            [
                'label' => Yii::t('app', 'OfficeMenuWidget.ordersTab'),
                'url'   => ['/user/list-orders', 'locale' => Yii::$app->request->get('locale')],
                'visible' => $this->showListOrders,
            ],
        ];
        if ($this->newOrdersCount > 0) {
            $items[2]['label'] .= '<span class="cabinet-app-count">'
                                . $this->newOrdersCount
                                . '</span>'
            ;
        }
        $this->items = $items;
    }

    protected function renderItems($items)
    {
        $n = count($items);
        $lines = [];
        foreach ($items as $i => $item) {
            $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
            $tag = ArrayHelper::remove($options, 'tag', 'li');
            $class = [];
            $item['class'] = 'cabinet__links-item';
            if ($item['active']) {
                $class[] = $this->activeCssClass;
                $item['class'] .= ' ' . $this->activeCssClass;
            }
            if ($i === 0 && $this->firstItemCssClass !== null) {
                $class[] = $this->firstItemCssClass;
            }
            if ($i === $n - 1 && $this->lastItemCssClass !== null) {
                $class[] = $this->lastItemCssClass;
            }
            if (!empty($class)) {
                if (empty($options['class'])) {
                    $options['class'] = implode(' ', $class);
                } else {
                    $options['class'] .= ' ' . implode(' ', $class);
                }
            }

            $menu = $this->renderItem($item);
            if (!empty($item['items'])) {
                $submenuTemplate = ArrayHelper::getValue($item, 'submenuTemplate', $this->submenuTemplate);
                $menu .= strtr($submenuTemplate, [
                    '{items}' => $this->renderItems($item['items']),
                ]);
            }
            $lines[] = Html::tag($tag, $menu, $options);
        }

        $lines[] = Html::a('+ '.Yii::t('app', 'ExcursionViewPage.addExcursionWidget'), ['/user/create-excursion', 'locale' => Yii::$app->request->get('locale')], ['class' => 'btn btn--minimal']);

        return implode("\n", $lines);
    }

    protected function renderItem($item)
    {
        if (isset($item['url'])) {
            $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);

            return strtr($template, [
                '{url}' => Html::encode(Url::to($item['url'])),
                '{label}' => $item['label'],
                '{class}' => $item['class'],
            ]);
        } else {
            $template = ArrayHelper::getValue($item, 'template', $this->labelTemplate);

            return strtr($template, [
                '{label}' => $item['label'],
            ]);
        }
    }
}
