<?php

namespace frontend\components\widgets\Breadcrumbs;

use Yii;
use yii\widgets\Breadcrumbs;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

class BreadcrumbsWidget extends Breadcrumbs
{
    public $tag = 'div';
    public $options = ['class' => 'breadcrumbs'];
    public $itemTemplate = "{link}\n";
    public $activeItemTemplate = "<span class=\"breadcrumbs__item\">{link}</span>\n";

    public function init()
    {
        parent::init();
        $this->homeLink = [
            'label' => Yii::t('yii', 'Home'),
            'url' => Url::to([
                'main-page/index',
                'locale' => Yii::$app->request->get('locale'),
                'target_audience' => Yii::$app->request->get('target_audience'),
            ]),
        ];
    }

    protected function renderItem($link, $template)
    {
        $encodeLabel = ArrayHelper::remove($link, 'encode', $this->encodeLabels);
        if (array_key_exists('label', $link)) {
            $label = $encodeLabel ? Html::encode($link['label']) : $link['label'];
        } else {
            throw new InvalidConfigException('The "label" element is required for each link.');
        }
        if (isset($link['template'])) {
            $template = $link['template'];
        }
        if (isset($link['url'])) {
            $options = $link;
            unset($options['template'], $options['label'], $options['url']);
            $options['class'] = 'breadcrumbs__item';
            $link = Html::a("<span>$label</span>", $link['url'], $options);
        } else {
            $link = $label;
        }
        return strtr($template, ['{link}' => $link]);
    }
}
