<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;
use frontend\components\helpers\BaseHelper;
use common\models\City;
use common\models\Sight;
use common\models\Tag;

class AutocompleteForm extends Model
{
    public $q;
    public $target_audience;

    public function rules()
    {
        return [
            ['q', 'trim'],
            [['q', 'target_audience'], 'safe'],
        ];
    }

    public function formName()
    {
        return '';
    }

    public function searchCities($limit)
    {
        $q = $this->q;

        if (empty($q)) {
            return [];
        }

        $result = [];

        $result =  City::find()
            ->joinWith('translations')
            ->andWhere(['like', '{{%city_translation}}.name', $q])
            ->all();

        $result = array_map(function ($item) use ($q) {
            $url = Url::to([
                            '/search/index',
                            'q' => $item->name,
                            'target_audience' => $this->target_audience,
                            'locale' => Yii::$app->language !== 'ru' ? 'en' : null,
            ]);
            $text = $item->name;
            if ($item->country) {
                $text .= ', ' . $item->country->name;
            }
            return [
                'href' => $url,
                'text' => $text,
            ];
        }, $result);

        return $result;
    }

    public function searchSights($limit)
    {
        $q = $this->q;

        if (empty($q)) {
            return [];
        }

        $result = [];

        $result =  Sight::find()
            ->joinWith('translations')
            ->andWhere(['like', '{{%sight_translation}}.name', $q])
            ->all();

        $result = array_map(function ($item) use ($q) {
            $url = Url::to([
                            '/search/index',
                            'q' => $item->name,
                            'target_audience' => $this->target_audience,
                            'locale' => Yii::$app->language !== 'ru' ? 'en' : null,
            ]);
            $text = $item->name;
            if ($item->city) {
                $text .= ', ' . $item->city->name;
            }
            return [
                'href' => $url,
                'text' => $text,
            ];
        }, $result);

        return $result;
    }

    public function searchTags($limit)
    {
        $q = $this->q;

        if (empty($q)) {
            return [];
        }

        $result = [];

        $result =  Tag::find()
            ->andWhere(['like', '{{%tag}}.name', $q])
            ->all();

        $result = array_map(function ($item) use ($q) {
            $url = Url::to([
                            '/search/index',
                            'q' => $item->name,
                            'target_audience' => $this->target_audience,
                            'locale' => Yii::$app->language !== 'ru' ? 'en' : null,
            ]);
            $text = $item->name;
            return [
                'href' => $url,
                'text' => $text,
            ];
        }, $result);

        return $result;
    }
}
