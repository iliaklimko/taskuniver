<?php

namespace frontend\components\widgets\PostCategoryFilter;

use Yii;
use yii\widgets\Menu;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use common\models\PostCategory;
use frontend\components\helpers\BaseHelper;

class PostCategoryFilterWidget extends Menu
{
    public $options = [
        'tag' => 'div',
        'class' => 'news-filter',
    ];
    public $itemOptions = ['tag' => null];
    public $linkTemplate = '<a href="{url}" class="news-filter__item news-filter__item--active">{label}</a>';
    public $activeCssClass = 'news-filter__item--active';

    public function init()
    {
        $items = array_map(function ($item) {
            return [
                'label' => $item->name,
                'url' => Url::to(BaseHelper::mergeWithCurrentParams(['post/index', 'post_category' => $item->url_alias])),
                'modelId' => $item->url_alias,
            ];
        }, $this->getPostCategoryList());
        $this->items = $items;
    }

    protected function renderItems($items)
    {
        $n = count($items);
        $lines = [];
        $active = false;
        foreach ($items as $i => $item) {
            $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
            $tag = ArrayHelper::remove($options, 'tag', 'li');
            $menu = $this->renderItem($item);
            if (!$this->isActive($item)) {
                $menu = str_replace($this->activeCssClass, '', $menu);
            }
            $active = $active ?: $this->isActive($item);
            $lines[] = Html::tag($tag, $menu, $options);
        }
        $firstElement = Html::a(
            Yii::t('app', 'PostCategoryFilterWidget.all'),
            Url::to(BaseHelper::mergeWithCurrentParams(['post/index', 'post_category' => 'all'])),
            [
                'class' => $active ? 'news-filter__item' : 'news-filter__item news-filter__item--active'
            ]
        );
        array_unshift($lines, $firstElement);

        return implode("\n", $lines);
    }

    protected function getPostCategoryList()
    {
        $categories = PostCategory::find()
            ->joinWith([
                'posts' => function ($query) {
                    $query->onCondition(['{{%post}}.language_code' => Yii::$app->language]);
                }
            ])
            ->orderBy('{{%post_category}}.priority DESC')
            // ->groupBy('{{%post_category}}.id')
            ->all();
        return array_filter($categories, function ($category) {
            return $category->posts;
        });
    }

    protected function isActive($item)
    {
        return Yii::$app->request->get('post_category') == $item['modelId'];
    }
}
